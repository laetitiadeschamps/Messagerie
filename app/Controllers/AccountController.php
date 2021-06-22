<?php 

namespace App\Controllers;

use App\Models\Roles;
use App\Models\Users;
use DateTime;

class AccountController extends CoreController {
    /**
     * Login method
     *
     * @return void
     */
    public function login() {
        $this->show('account/login');  
    }
    /**
     * Maccount creation method
     *
     * @return void
     */
    public function create() {
        $this->show('account/create');
    }
    /**
     * logout method
     *
     * @return void
     */
    public function logout() {
        unset($_SESSION['id']);
        unset($_SESSION['userObject']);
        unset($_SESSION['role']); 
        $this->redirect('account-login');
        exit; 
    }
     /**
     * user profile method
     *
     * @return void
     */

    public function detail() {
        $token = bin2hex(random_bytes(32));
        $_SESSION['token'] = $token;
        $this->show('account/detail', ['user'=>Users::find($_SESSION['id']), 'token'=>$token]);  
    }

     /**
     * method for processing update of user profile
     *
     * @return void
     */
    public function update() {
       // Same treatments as the account creation method except that the user is logged in so there is a $_SESSION['id']
       $this->save();
    }

     /**
     * Method for processing both account creation and user profile update, according to whether a user is logged in or not
     *
     * @return void
     */
    public function save() {  
        // If a user is logged in, there is a $_SESSION['id'] and we are therefore on an update situation
        $id = $_SESSION['id'] ?? '';
        if(!$id) {
            $mail = addslashes(trim(filter_input(INPUT_POST, 'mail', FILTER_VALIDATE_EMAIL)));
            $login = addslashes(trim(filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING)));
            $password = addslashes(trim(filter_input(INPUT_POST, 'password'))); 
          
        } else {
            $description = addslashes(trim(filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING))); 
            // If there is a photo loaded, we upload it to the server, if it is an avatar, we just update $avater variable
            if(trim($_FILES["photoUpload"]['name'])) {
                
                $target_dir = "../public/assets/images/";
                $target_file = $target_dir . basename($_FILES["photoUpload"]['name']);
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $avatar = date('YmdHi') . '.' . $imageFileType;
                $isPhoto = true;
            } else {
                $avatar = trim(addslashes(filter_input(INPUT_POST, 'avatar', FILTER_SANITIZE_STRING))); 
                $isPhoto = false;  
            }
             
        }
        
        $lastname = addslashes(trim(filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING)));
        $firstname = addslashes(trim(filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING)));
        $birthdate = addslashes(trim(filter_input(INPUT_POST, 'birthdate')));
       
        // According to whether we are on a creation or an update, we set the appropriate values in our user object
        if(!$id) {
            $user = new Users();
            $mailResponse = $user->setMail($mail);
            $loginResponse = $user->setLogin($login);
            $passwordResponse = $user->setPassword($password);
        } else {
            $user = Users::find($id); 
            $descriptionResponse = $user->setDescription($description);
            $avatarResponse = $user->setPicture($avatar);
        }
        $lastnameResponse = $user->setLastname($lastname);
        $firstnameResponse = $user->setFirstname($firstname);
        $birthdateResponse = $user->setBirthdate($birthdate);
        // If creation, we check for similar mail or login
        if(!$id) {  
            if(Users::findByMail($mail)) {
                $this->addFlashMessage('error', "Ce mail existe déjà");
                $this->addFlashMessage('object', $user);
                $this->redirect('account-create');
                exit; 
            }
            if(Users::findByLogin($login)) {
                $this->addFlashMessage('error', "Ce nom d'utilisateur existe déjà");
                $this->addFlashMessage('object', $user);
                $this->redirect('account-create');
                exit; 
            }
      }
      if(!$id) {
        if($mailResponse && $loginResponse && $lastnameResponse && $firstnameResponse && $birthdateResponse && $passwordResponse) {
            try {
              $user->save();
              $this->addFlashMessage('info', "Votre compte a bien été créé, vous pouvez vous connecter");
              $this->redirect('main-home');
            } catch(\Exception $e) {
                $this->addFlashMessage('error', 'Il y a eu un problème');
                $this->addFlashMessage('object', $user);
                $this->redirect('account-create');
                exit;
            }
         } else {
             $this->addFlashMessage('error', 'Veuillez revérifier vos champs');
             $this->addFlashMessage('object', $user);
             $this->redirect('account-create');
             exit;
         }

      } else {
 
        if($lastnameResponse && $firstnameResponse && $birthdateResponse && $descriptionResponse  && $avatarResponse &&  $this->handlePhotoUpload($_FILES["photoUpload"])) {
            try {
               
              $user->save();
              $this->addFlashMessage('info', "Votre compte a bien été mis à jour");
              $_SESSION['userObject'] = $user;
              $this->redirect('account-detail');
              exit;
            } catch(\Exception $e) {
                $this->addFlashMessage('error', 'Il y a eu un problème');
                $this->addFlashMessage('object', $user);
                $this->redirect('account-detail');
                exit;
            }
         } else {
             $this->addFlashMessage('error', 'Veuillez revérifier vos champs');
             $this->addFlashMessage('object', $user);
             $this->redirect('account-detail');
             exit;
         }
      }    
    }

     /**
     * Method for processing login
     *
     * @return void
     */
    public function loginPost() {
       $login = addslashes(trim(filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING)));
       $password = addslashes(trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING)));
       $userModel = Users::findByMail($login);
       // If we find a user with same login, we check the password hash
       if($userModel) { 
            if(password_verify($password, $userModel->getPassword())) {
                $_SESSION['id'] = $userModel->getId();
                $_SESSION['userObject'] = $userModel;
                $role_id = $userModel->getRoleId();
                $_SESSION['role'] = Roles::find($role_id)->getName();
                $this->redirect('main-home');
                exit;
            } 
            else {
                $_SESSION['isConnected'] = false;
                $this->addFlashMessage('error', 'Les informations de connexion sont incorrectes');
                $this->redirect('account-login');
                exit;
            }
        
        } else {
            $_SESSION['isConnected'] = false;
            $this->addFlashMessage('error', 'Les informations de connexion sont incorrectes');
            $this->redirect('account-login');
            exit;
        }
    }
   
}