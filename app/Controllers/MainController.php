<?php

namespace App\Controllers;
use App\Models\Chats;
use App\Models\Messages;
use App\Models\Users;

 /**
     * Method for displaying home page and notifications
     *
     * @return void
*/
class MainController extends CoreController {
    public function home(){
        $user = Users::find($_SESSION['id']);
        //Get total number of unread messages
        //$countUnread = $user->findUnreadCount();
        //Get unread messages per chat
        $contacts = Users::findUnreadMessageCount();
        $countUnread = count($contacts);
        //For each contact, we add a chat value with chat id for this user
        foreach($contacts as $key=>$contact) {
            $contacts[$key]['chat']= $user->findChatWithSingleUser($contact['user_id']);
        }
        // We sort contacts according to latest message
        usort($contacts, function($a, $b) {
            return strcmp($b["chat"]->updated_at, $a["chat"]->updated_at);
        }); 

       
       
        $requests = $user->findFriendRequests();
        $requestsCount = count($requests);
       
        $token = bin2hex(random_bytes(32));
        $_SESSION['token'] = $token;

        $this->show('main/home', ['notifications'=>$contacts, 'countUnread'=>$countUnread, 'requestsCount'=>$requestsCount, 'requests'=>$requests, 'token'=>$token]);
        
    }
   
}
