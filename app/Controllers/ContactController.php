<?php

namespace App\Controllers;

use App\Models\Chats;
use App\Models\Users;

class ContactController extends CoreController {

    /**
     * Method for displaying contact list
     * @return void
     */
    public function list(){
        $token = bin2hex(random_bytes(32));
        $_SESSION['token'] = $token;
        $user = Users::find($_SESSION['id']);
        $contacts = $user->findContacts();
       
        $this->show('contact/list', ['contacts'=>$contacts, 'token'=>$token]);
        
    }
    /**
     * Method for displaying a specific user's profile
     * @return void
     */
    public function profile(int $id) { 
        $token = bin2hex(random_bytes(32));
        $_SESSION['token'] = $token;
        $user= Users::find($_SESSION['id']);
        $contact = Users::find($id);
        // Finding chat id for the current user
        $chat = $user->findChatWithSingleUser($contact->getId());
       // Checking if the user is friends with the current logged in user
        $isFriend = $contact->isFriend();
       
        $this->show('contact/profile', ['user'=>$contact, 'isFriend'=>$isFriend, 'chat'=>$chat, 'token'=>$token]);
    }
    /**
     * Method for unfriending a user
     * @return void
     */
    public function unfriend(int $id) {

        $user = Users::find($_SESSION['id']);
        if($user->unfriend($id)) {
            $this->addFlashMessage('info', "Liste de contacts mise à jour");
        } else {
            $this->addFlashMessage('error', "Il y a eu une erreur, veuillez réessayer");
        }  
        $this->redirect('contact-list');
    }
     /**
     * Method for befriending a user
     * @return void
     */
    public function befriend(int $id) {
       
        $user = Users::find($_SESSION['id']);
        if($user->befriend($id)) {
            $this->addFlashMessage('info', "Votre liste d'amis a été mise à jour");
            // If chat exists do nothing
            //If not, create chat
            if(!$user->findChatWithSingleUser($id)) {
                $chat = new Chats();
                $chat->save();            
                $chat->addUsersToChat($id);
            }
            
        } else {
            $this->addFlashMessage('error', "Il y a eu une erreur, veuillez réessayer");
        }
       
        $this->redirect('main-home');
    }
    /**
     * Method for requesting to befriend a user
     * @return void
     */
    public function requestBefriend(int $id) {
       
        $user = Users::find($_SESSION['id']);
        if($user->requestFriend($id)) {
            $this->addFlashMessage('info', "La demande d'ajout a été envoyée");
           
        } else {
            $this->addFlashMessage('error', "Il y a eu une erreur, veuillez réessayer");
        }
       
        $this->redirect('contact-profile', ['id'=>$id]);
    }
     /**
     * Method for rejecting friend request
     * @return void
     */
    public function rejectBefriend(int $id) {
        
        $user = Users::find($_SESSION['id']);
       
        if($user->rejectFriendRequest($id)) {
            $this->addFlashMessage('info', "La demande d'ajout a été supprimée");
            
            
        } else {
            $this->addFlashMessage('error', "Il y a eu une erreur, veuillez réessayer");
        }
       
        $this->redirect('main-home');
    }
    /**
     * Method for finding users according to search query (API Mode)
     * @return void
     */
    public function listAllUsers($string) {
        $users = Users::findWithString($string);  
        header('Content-type: application/json');
        header('Access-Control-Allow-Origin', '*');
        header('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE');
        echo json_encode($users);   
    }
   
}
