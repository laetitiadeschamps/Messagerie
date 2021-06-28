<?php

namespace App\Controllers;

use App\Models\Chats;
use App\Models\Messages;
use App\Models\Users;
use DateTime;

class ChatController extends CoreController {
   
     /**
     * Method for displaying a specific chat as per id
     *
     * @return void
     */
    public function getChat(int $id) {
        //get token for authentification
        $token = bin2hex(random_bytes(32));
        $_SESSION['token'] = $token;

        $chat = Chats::find($id);
        if(!$chat) {
            header('HTTP/1.0 403 Forbidden'); 
            die('You are not allowed to access this file.'); 
        }
        
        //For the chat, find the user that is not the current user 
        $contacts = $chat->findUsers();
       
        // If our logged in user is not part of the chat's users we return a 403
        $isAuthorized = false;
        foreach ($contacts as $contact) {
            $id = $contact->id;
            if($id == $_SESSION['id']) {
                $isAuthorized = true;
            }
        }
        if(!$isAuthorized) {
            header('HTTP/1.0 403 Forbidden'); 
            die('You are not allowed to access this file.'); 
        }
       

        $contacts = array_filter($contacts, function($contact){
            return $contact->id != $_SESSION['id'];
        });
        //We get an array, and keep only the first result
        foreach($contacts as $contactUser) {
            $contact=$contactUser;
            break;
        }
        $messages = $chat->findMessages();
        // When the current user enters the chat, we set the status of the messages to "read", if he is not the author
        foreach($messages as $message) { 
            if($message->getAuthorId() !== $_SESSION['id']) {
                $message->setIsRead(true);
                $message->update();
            }     
        }
        $this->show('chat/chat', ['messages'=>$messages, 'contact'=>$contact,  'chat'=>$chat, 'token'=>$token]);   
    }
    
    /**
     * Method for displaying the list of all current chats
     *
     * @return void
     */
    public function list() {
        $token = bin2hex(random_bytes(32));
        $_SESSION['token'] = $token;
        
        $chats = Chats::findCurrentChats();
        // For each chat of the current user, we add a message property containing the last message of the chat
        foreach($chats as $key=>$contact) {
            $chats[$key]['message']=Messages::getLast($chats[$key]['chat_id']);
        }
        // We only display chats that have messages
        $activeChats = array_filter($chats, function($chat){
            return $chat['message'] == true;
        });
        //And we sort them by last message date
        usort($activeChats, function($a, $b) {
            return strcmp($b["message"]->created_at, $a["message"]->created_at);
        }); 
        $this->show('chat/list', ['activeChats'=>$activeChats, 'token'=>$token]);
    }

    /**
     * Method for deleting a chat
     *
     * @return void
     */
    public function delete(int $id) {
        $chat = Chats::find($id);
        $chat->delete();
        $this->redirect('chat-list');
        exit;

    }

     /**
     * Method for changing status of the messages of a specific chat to "read" if user that is not the author is on that (API mode)
     *
     * @return void
     */
    public function markAsRead(int $chatId) {
        $chat = Chats::find($chatId);
        $messages = $chat->findMessages(false);
        foreach ($messages as $message) {
            if ($message->getAuthorId() !== $_SESSION['id']) {
                $message->setIsRead(true);
                $message->update();
            }
        }
        header('Content-type: application/json');
        header('Access-Control-Allow-Origin', '*');
        header('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE');
        echo json_encode('ok');
    }
    /**
     * Method for sending a message on the chat, without waiting for page reload (API mode)
     *
     * @return void
     */
    public function sendMessage() {
       // Retrieving data from fetch
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        if ($contentType === "application/json") {
            //Receiving the RAW post data.
            $content = trim(file_get_contents("php://input"));
            $decoded = json_decode($content, true);

            // Creating a new message object with appropriate properties, and saving it
            $chat_id = $decoded['chatId'];
            $message_body = filter_var($decoded['newPostMessage'], FILTER_SANITIZE_SPECIAL_CHARS);
            $message = new Messages();
            $message->setChatId($chat_id);
            $message->setMessage($message_body);
            $message->setAuthorId($_SESSION['id']);
            $message->save();

            //Updating chat updated at property (used to sort chats according to most recent messages)
            $chat = Chats::find($chat_id);
            $chat->setUpdatedAt(new DateTime());
            $chat->save();

            //Sending response to be displayed on appropriate chat
            header('Content-type: application/json');
            header('Access-Control-Allow-Origin', '*');
            header('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE');
            echo json_encode($decoded['chatId']);
           
        }
       
    }

    
}
