<?php

namespace App\Controllers;

use DateTime;

class CoreController {
   
    public function __construct() {
        // Granting access to pages according to role 
        $acl =[
            'main-home'=>['admin', 'user'],
            'account-detail'=>['admin', 'user'],
            'account-update'=>['admin', 'user'],
            'account-logout'=>['admin', 'user'],
            'chat-list'=>['admin', 'user'],
            'chat-getChat'=>['admin','user'], 
            'chat-sendMessage'=>['admin','user'],
            'chat-delete'=>['admin', 'user'],
            'contact-list'=>['admin', 'user'],
            'contact-listAllUsers'=>['admin', 'user'],
            'contact-profile'=>['admin', 'user'],
            'contact-befriend'=>['admin', 'user'],
            'contact-unfriend'=>['admin', 'user']
        ];
       
        global $match;
        $routeName = $match['name']?? '';
       
        if(isset($acl[$routeName])){
            $authorizedRoles = $acl[$routeName];
            $this->checkAuthorization($authorizedRoles);
        }
        $this->checkTokenCsrf($routeName);
    }
    /**
     * Method for adding flash messages or objects
     * @return void
     */
    public function addFlashMessage($type, $message ) {
      
        switch ($type) {
            case "error" : 
                $_SESSION['errors'][] = $message;
                break;
            case "info" : 
                $_SESSION['infos'][] = $message;   
                break;
            case 'object' : 
                $_SESSION['object'] = $message;
                break;
        }   
       
    }
     /**
     * Method for handling photo upload
     *
     * @return void
     */
   
    public function handlePhotoUpload($file=NULL) {
        if($file['name']){
            $target_dir = "../public/assets/images/";
            $target_file = $target_dir . basename($file['name']);
        
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $newName = $target_dir . explode('.', $target_file)[0] . date('YmdHi') . '.' . $imageFileType;

            // Check if image file is a actual image or fake image
            $check = getimagesize($file["tmp_name"]);
            if($check == false) {
            $this->addFlashMessage('error', "Le fichier fourni n'est pas une image");
                $uploadOk = 0;
            }

            // Check file size
            if ($file["size"] > 500000) {
                $this->addFlashMessage('error', "Désolé, le fichier est trop gros");
                $uploadOk = 0;
            }

            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                $this->addFlashMessage('error', "Désolé, seuls les fichiers JPG, JPEG, PNG & GIF sont autorisés");
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $this->addFlashMessage('error', "Désolé, le fichier n'a pas pu être chargé");
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($file["tmp_name"], $newName)) {
                return true;
                } else {
                $this->addFlashMessage('error', "Il y a eu un problème lors de l'envoi");
                }
            }
        return false;
        } else {
            return true;
        }
        
    }

    /**
     * Method for securing sensible routes with csrf token
     * @return void
     */
    public function checkTokenCsrf($routeName) {
        //List of routes to protect
        $csrfTokenToCheck = [
            'account-update',
            'chat-delete',
            'contact-befriend',
            'contact-unfriend',
            'contact-requestBefriend',
            'contact-rejectBefriend'   
        ];
    
        
        if (in_array($routeName, $csrfTokenToCheck)) {
            
            $token = $_POST['token'] ?? $_GET['token'] ?? '';
            $sessionToken = $_SESSION['token'] ?? '';
            
            if (empty($token) || $token !== $sessionToken) {
           
                
                http_response_code(403);
                $this->show('error/err403');
                exit;
            }
            else {
                unset($_SESSION['token']);
            }
        }
    }
    /**
     * Method for redirecting using router
     * @return void
     */
    public function redirect($routeName, $params = []) {
        global $router;

        header('Location:'. $router->generate($routeName, $params));
    }

    /**
     * Method for checking role authorization according to roles defined for each route in the construct
     * @return void
     */
    public function checkAuthorization($roles) {
        global $router; 
        
        if(isset($_SESSION['userObject'])) {
            if(in_array($_SESSION['role'], $roles)) {  
                return true;
            } else {
                http_response_code(403);
                $this->show('error/err403');
                exit; 
            }
        } else {
            header('Location:' . $router->generate('account-login'));
            exit;
        }
       
    }

     /**
     * Method for displaying views
     *
     * @return void
     */
    protected function show(string $viewName, $viewVars = []) {
        // On globalise $router car on ne sait pas faire mieux pour l'instant
        global $router;
       
       
        $viewVars['currentPage'] = $viewName;
        
        $viewVars['baseUri'] = 'http://' . $_SERVER['HTTP_HOST'];
        
        extract($viewVars);
    
        require_once __DIR__.'/../views/layout/header.tpl.php';
        require_once __DIR__.'/../views/'.$viewName.'.tpl.php';
        require_once __DIR__.'/../views/layout/footer.tpl.php';
    }
}
