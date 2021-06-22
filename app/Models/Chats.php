<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

/**
 * Un modèle représente une table (un entité) dans notre base
 * 
 * Un objet issu de cette classe réprésente un enregistrement dans cette table
 */
class Chats extends CoreModel {
  
   
    private $name;
    private $type_id;

     /**
     * Method called on befriend if no chat exists between the two users
     * @param integer $id
     * @return bool
     */
    public function addUsersToChat(int $id)  {

        $pdo = Database::getPDO();
        $sql = 'INSERT INTO `chat_users` (`chat_id`, `user_id`)
        VALUES (:chat_id, :user_id);
        INSERT INTO `chat_users` (`chat_id`, `user_id`)
        VALUES (:chat_id, :friend_id)';
      
      $pdoStatement = $pdo->prepare($sql);
      $insertedRows = $pdoStatement->execute([
        ':chat_id'=>$this->id,
        ':user_id'=>$_SESSION['id'],
        ':friend_id'=>$id
   
        ]);
        if ($insertedRows > 0) {
            return true;
        }
        return false;
    
    }
    /**
     * Method to delete a chat 
     * @return bool
     */
    public function delete() {
        $pdo = Database::getPDO();
        $sql = 'DELETE FROM messages WHERE chat_id = :chat_id';
        $pdoStatement = $pdo->prepare($sql);
        $deletedRows = $pdoStatement->execute([
        ':chat_id'=>$this->id,
   
        ]);
        if ($deletedRows > 0) {
            $this->id = $pdo->lastInsertId();  
            return true;
        }
        return false; 
    }

     /**
     * Method to find a chat based on its id
     * @param integer $id
     * @return Chats  
     */
    public static function find(int $id){
       
        $pdo = Database::getPDO();

        $sql = 'SELECT * FROM `chats` WHERE `id` = ' . $id;

        
       $result = $pdo->query($sql); 

        $chat = $result->fetchObject(self::class);
       
        // retourner le résultat
        return $chat;
    }
    
    /**
     * Method to find all existing chats for the logged in user
     *
     * @return Collection|Chats[]
     */
    public static function findAll(){
        $pdo = Database::getPDO();
       
            $sql = 'SELECT chats.*
            FROM chats
            INNER JOIN chat_users
            ON chats.id = chat_users.chat_id
            WHERE `chat_users`.user_id = :user_id';
             $pdoStatement = $pdo->prepare($sql);
             $pdoStatement->execute([
               ':user_id'=>$_SESSION['id'],
          
           ]);
           $chats = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
 
       return $chats;
       
    }
    /**
     * Method to find all current chats for the logged in user
     *
     * @return array
     */
    public static function findCurrentChats() {
    
        $pdo = Database::getPDO();

        $sql = 'SELECT users.*, chat_users.chat_id as chat_id
        FROM `chat_users` 
        INNER JOIN users
        ON chat_users.user_id = users.id
        WHERE chat_users.user_id != :user_id AND chat_users.chat_id IN (
            SELECT chat_id
            FROM chat_users
            WHERE user_id = :user_id
        )
        GROUP BY chat_users.chat_id
        ' ;
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->execute([
            ':user_id'=>$_SESSION['id']
       
        ]);
           
        $chats = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        return $chats;
    }
    /**
     * Method to find all messages for one specific chat or just find the unread ones
     * @param bool
     * @return Collection|Messages[]
     */
    public function findMessages($is_read = true) {
        $pdo = Database::getPDO();
        if($is_read) {
            $sql = 'SELECT messages.*, users.login as author_name FROM `messages` INNER JOIN `users` ON users.id = messages.author_id WHERE `chat_id` = :chat_id 
            ORDER BY created_at DESC' ;

        } else {
            $sql = 'SELECT messages.*, users.login as author_name FROM `messages` INNER JOIN `users` ON users.id = messages.author_id WHERE `chat_id` = :chat_id AND messages.is_read = false
            ORDER BY created_at DESC' ;
        }
        
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->execute([
            ':chat_id'=>$this->getId(),
       
        ]);
          
        $messages = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Messages');
       
        return $messages;
    }

    /**
     * Method to find all users for one specific chat
     *
     * @return Collection|Users[]
     */
    public function findUsers(){
        $pdo = Database::getPDO();
        $sql = 'SELECT users.*
        FROM chat_users
        INNER JOIN users
        ON chat_users.user_id = users.id
        WHERE chat_id =:chat_id';
          
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->execute([
            ':chat_id'=>$this->id,
        ]);
        $users = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Users');
        return $users;
       
    }
    /**
     * Method to create or update a chat
     *
     * @return bool
     */
    public function save() {
        $pdo = Database::getPDO();
        if($this->getId()) {
            $sql = 'UPDATE `chats` SET updated_at= NOW()
            WHERE id = ' . $this->id;
        } else {
            $sql = 'INSERT INTO `chats` (`type_id`, `name`)
            VALUES (1, "chat")';   
        }
        $insertedRows = $pdo->exec($sql); 
        if ($insertedRows > 0) {
            $this->id = $pdo->lastInsertId();  
            return true;
        }
        return false;
   
    }

    
    /**
     * Get the value of type_id
     */ 
    public function getTypeId()
    {
        return $this->type_id;
    }

    /**
     * Set the value of type_id
     *
     * @return  self
     */ 
    public function setTypeId($type_id)
    {
        $this->type_id = $type_id;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}