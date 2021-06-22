<?php

namespace App\Models;

use App\Utils\Database;
use DateTime;
use PDO;

/**
 * Un modèle représente une table (un entité) dans notre base
 * 
 * Un objet issu de cette classe réprésente un enregistrement dans cette table
 */
class Users extends CoreModel {
  
    public $mail;
    public $login;
    public $password;
    public $firstname;
    public $lastname;
    public $role_id;
    public $status;
    public $picture;
    public $birthdate;
    public $description;

    /**
     * Method to add a new friend to our logged in user
     * @param int
     * @return bool
     */
    public function befriend(int $id) {
        
        $pdo = Database::getPDO();
        $sql = 'INSERT INTO `has_friend` (`user_id`, `friend_id`)
        VALUES (:user_id, :friend_id);
        INSERT INTO `has_friend` (`user_id`, `friend_id`)
        VALUES (:friend_id, :user_id)';
        
        $pdoStatement = $pdo->prepare($sql);
        $insertedRows = $pdoStatement->execute([
            ':user_id'=>$this->getId(),
            ':friend_id'=>$id,   
        ]);
        if ($insertedRows > 0) {
            return true;
        }
        return false;
    
    }
    /**
     * Method to find one user based on his id
     * @param int
     * @return Users
     */
    public static function find(int $id){
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `users` WHERE `id` = ' . $id;
        $result = $pdo->query($sql); 
        $user = $result->fetchObject(self::class);
        return $user;
    }
    /**
     * Method to find all users
     * @return Collection|Users[]
     */
    public static function findAll() {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `users`'; 
        $result = $pdo->query($sql); 
        $users = $result->fetchAll(PDO::FETCH_CLASS, self::class);
        return $users;
    }
    
    /**
     * Method to find a user based on his login
     * @param string
     * @return Users
     */
    public static function findByLogin(string $login) {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `users`
        WHERE `login` = :login && `status` = 1';
        $pdoStatement = $pdo->prepare($sql); 
        $pdoStatement->execute([
            ':login'=>$login
        ]);
        $user = $pdoStatement->fetchObject(self::class);
        return $user;
    }
    /**
     * Method to find a user based on his email
     * @param string
     * @return Users
     */
    public static function findByMail($mail) {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `users`
        WHERE mail = :mail && `status` = 1';
        
        $pdoStatement = $pdo->prepare($sql); 
        $pdoStatement->execute([
            ':mail'=>$mail
        ]);
        $user = $pdoStatement->fetchObject(self::class);
        return $user;
    }
    /**
     * Method to find the chat between a specific contact and the logged in user 
     * @param int
     * @return Chats
     */
    public function findChatWithSingleUser(int $id){
        $pdo = Database::getPDO();
        $sql = 'SELECT chats.*
        FROM chats
        INNER JOIN chat_users
        ON chats.id = chat_users.chat_id
        WHERE `chat_users`.user_id = :id AND chats.id IN (
        SELECT chat_id
        FROM chat_users
        WHERE user_id = :user_id 
        ) ';
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->execute([
            ':user_id'=>$this->id,
            ':id'=>$id
        ]);
        $chat = $pdoStatement->fetchObject('App\Models\Chats');
        return $chat;
       
    }
    /**
     * Method to find all friends of the logged in user
     * @return Collection|Users[]
     */
    public function findContacts(){
        $pdo = Database::getPDO();
        $sql = 'SELECT users.*, chat_users.chat_id as chat_id
        FROM chat_users
        INNER JOIN users
        ON chat_users.user_id = users.id
        INNER JOIN has_friend
        ON has_friend.friend_id = users.id
        WHERE has_friend.user_id = :id AND chat_id IN (
            SELECT chat_id
            FROM chat_users
            WHERE user_id = :id)
        ORDER BY users.lastname ASC';
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->execute([
            ':id'=>$this->id
        ]);

        $users = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Users');
        return $users;   
    }
     /**
     * Method to find the number of unread messages for the logged in user
     * @return int
     */
    public function findUnreadCount() {
        $pdo = Database::getPDO();
        $sql = 'SELECT COUNT(*) as quantity FROM `messages`
        INNER JOIN chat_users
        ON chat_users.chat_id = messages.chat_id
        WHERE messages.is_read = false AND chat_users.user_id = :id AND messages.author_id !=:id
        ';
        $pdoStatement = $pdo->prepare($sql); 
        $pdoStatement->execute([
            ':id'=>$this->id
        ]);
        $count = $pdoStatement->fetch();
        return $count;

    } 
    /**
     * Method to find the number of unread messages for one specifique user
     *
     * @return array
     */
    public static function findUnreadMessageCount() {

        $pdo = Database::getPDO();
        $sql='SELECT user_id, COUNT(*) as message_quantity
        FROM messages
        INNER JOIN chat_users
        ON messages.chat_id = chat_users.chat_id
        WHERE chat_users.chat_id IN (
            SELECT chat_id
            FROM chat_users
            WHERE user_id = :user_id
        )  AND is_read= false AND author_id != :user_id AND user_id != :user_id
        GROUP BY user_id
        ';

        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->execute([
            ':user_id'=>$_SESSION['id']
       
        ]);   
        $messages = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
     
        return $messages;
    }
    
    /**
     * Method to create or update a user
     * @return bool
     */
    public function save() {
        $pdo = Database::getPDO();
        if($this->getId()) {
            $sql = "UPDATE `users` SET firstname=:firstname, lastname=:lastname, birthdate=:birthdate, `description`=:description, picture=:avatar, role_id=:role_id, `status`=:status WHERE `id`= " . $this->getId();
            $pdoStatement = $pdo->prepare($sql);
            $insertedRows = $pdoStatement->execute([
            ':firstname'=>$this->firstname,
            ':lastname'=> $this->lastname, 
            ':birthdate'=>$this->birthdate,
            ':description'=>$this->description,
            ':avatar'=>$this->picture,
            ':role_id'=> $this->role_id, 
            ':status'=>$this->status
            ]);
            $pdoStatement = $pdo->prepare($sql); 
        } else {
            $sql = "INSERT INTO `users` (mail, login, password, firstname, lastname, birthdate, role_id, status)
            VALUES (:mail, :login, :password, :firstname, :lastname, :birthdate, :role_id, :status)
            ";
            $pdoStatement = $pdo->prepare($sql);
            $insertedRows = $pdoStatement->execute([
                ':mail'=>$this->mail,
                ':login'=>$this->login,
                ':password'=> $this->password,
                ':firstname'=>$this->firstname,
                ':lastname'=> $this->lastname, 
                ':birthdate'=>$this->birthdate,
                ':role_id'=> 4, 
                ':status'=>1 
            ]);
        }
         
        if ($insertedRows > 0) {
            $this->id = $pdo->lastInsertId();
            return true;
        }
        return false;
    }
    
    /**
     * Method to unfriend a contact
     * @param int
     * @return bool
     */
    public function unfriend(int $id) {
        $pdo = Database::getPDO();
        $sql = 'DELETE FROM `has_friend`
        WHERE `user_id`= :user_id AND friend_id = :friend_id;';
          
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->execute([
            ':user_id'=>$this->getId(),
            ':friend_id'=>$id        
        ]);
        $sql = 'DELETE FROM `has_friend`
        WHERE `friend_id`= :friend_id AND user_id = :user_id';
        $pdoStatement = $pdo->prepare($sql);
        $deletedRows = $pdoStatement->execute([
             ':user_id'=>$id,
             ':friend_id'=>$this->getId(),     
         ]);
       
        if ($deletedRows > 0) {
            return true;
        }
        return false;
    
    }
   
    /**
     * Get the value of mail
     */ 
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set the value of mail
     *
     * @return  self
     */ 
    public function setMail($mail)
    {
        if($mail) {
            $this->mail = $mail;
            return true;
        } else {
            return false;
        }

    }

    /**
     * Get the value of login
     */ 
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set the value of login
     *
     * @return  self
     */ 
    public function setLogin($login)
    {
        if($login && strlen($login) > 2) {
            $this->login = $login;
            return true;
        } else {
            return false;
        }
       

        
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    { 
        $regex = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)([-+!*$@%_\w]{7,})$/';
    
    if(preg_match($regex, $password)) {
        
        $this->password = password_hash($password, PASSWORD_DEFAULT);
       
        return $this->password;
    } else {
       
        return false;

    }
        
    }

    /**
     * Get the value of firstname
     */ 
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @return  self
     */ 
    public function setFirstname($firstname)
    {
       
        if($firstname && strlen($firstname) > 2) {
            $this->firstname = $firstname;
          
            return true;
            
        } else {
            return false;
        }
    }

   
    /**
     * Get the value of role
     */ 
    public function getRoleId()
    {
        return $this->role_id;
    }

    /**
     * Set the value of role
     *
     * @return  self
     */ 
    public function setRoleId($role)
    {
        $this->role_id = $role;

        return $this;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of picture
     */ 
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set the value of picture
     *
     * @return  self
     */ 
    public function setPicture($picture)
    {
        if($picture) {
            $this->picture = $picture;
            return true;
        } else {
            return false;
        }
        
    }

    /**
     * Get the value of birthdate
     */ 
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set the value of birthdate
     *
     * @return  self
     */ 
    public function setBirthdate($birthdate)
    {
        if(new DateTime($birthdate) && new DateTime($birthdate) < new DateTime() ) {
            $this->birthdate = $birthdate;
            return true;
        } else {
            return false;
        }
        

    }
    public function isFriend() {
        $pdo = Database::getPDO();
        $sql = 'SELECT * 
        FROM `has_friend`
    
        WHERE `user_id` = :user_id AND friend_id = :friend_id';
        
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->execute([
        ':user_id'=>$_SESSION['id'],
        ':friend_id'=>$this->id
    
        ]);
            $users=$pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
            if($users) {
                return true;
            } else {
                return false;
            }
        
           

    }

    public static function findWithString($string) {
        $pdo = Database::getPDO();
    $sql = 'SELECT * 
    FROM `users`
 
    WHERE (`lastname`LIKE :string OR `firstname` LIKE :string) AND role_id=4';
      
    $pdoStatement = $pdo->prepare($sql);
    $pdoStatement->execute([
    ':string'=>"%$string%"
   
    ]);
        $users=$pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
        
     
        return $users;
 
      }

    /**
     * Get the value of description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription($description)
    {
     
            $this->description = $description;
            return true;
       
        
    }

    /**
     * Get the value of lastname
     */ 
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @return  self
     */ 
    public function setLastname($lastname)
    {
        if($lastname && strlen($lastname) > 2) {
            $this->lastname = $lastname;
            return true;
        } else {
            return false;
        }
    }
}