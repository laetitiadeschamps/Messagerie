<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

/**
 * Un modèle représente une table (un entité) dans notre base
 * 
 * Un objet issu de cette classe réprésente un enregistrement dans cette table
 */
class Messages extends CoreModel {
  
   
    private $chat_id;
    private $message;
    private $author_id;
    private $isRead;

    /**
     * Method to get last message of a chat
     * @param int
     * @return Messages
     */
    public static function getLast(int $chat_id) {
        $pdo = Database::getPDO();
        $sql='SELECT messages.*
        FROM messages
        INNER JOIN chats
        ON messages.chat_id = chats.id
        WHERE messages.chat_id = :chat_id
        ORDER BY messages.created_at DESC
        ';
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->execute([
            ':chat_id'=>$chat_id
        ]);   
        $message = $pdoStatement->fetchObject(self::class);
        return $message;
        
    }
    /**
     * Method to update a message, and more specifically mark message as read
     *
     * @return bool
     */
    public function update() {
        $pdo = Database::getPDO();
        $sql = 'UPDATE `messages` SET is_read = true
        WHERE id = :message_id';
        $pdoStatement = $pdo->prepare($sql);
        $insertedRows = $pdoStatement->execute([
            ':message_id'=>$this->id,
        ]);
        if ($insertedRows > 0) {
            return true;
        }
        return false;
    }

    /**
     * Method to create a message
     *
     * @return bool
     */
    public function save() {
      
        $pdo = Database::getPDO();
        $sql = 'INSERT INTO `messages` (`chat_id`, `message`, `author_id`)
        VALUES (:chat_id, :message, :author_id)';
      
        $pdoStatement = $pdo->prepare($sql);
        $insertedRows = $pdoStatement->execute([
            ':chat_id'=>$this->chat_id,
            ':message'=>$this->message,
            ':author_id'=>$this->author_id
        ]);
        if ($insertedRows > 0) {
            return true;
        }
        return false;
        }

    /**
     * Get the value of name
     */ 
    public function getChatId()
    {
        return $this->chat_id;
    }

    /**
     * Set the value of chat_id
     *
     * @return  self
     */ 
    public function setChatId($chat_id)
    {
        $this->chat_id = $chat_id;

        return $this;
    }
    /**
     * Get the value of name
     */ 
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @return  self
     */ 
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }
    /**
     * Get the value of name
     */ 
    public function getAuthorId()
    {
        return $this->author_id;
    }

    /**
     * Set the value of author_id
     *
     * @return  self
     */ 
    public function setAuthorId($author_id)
    {
        $this->author_id = $author_id;

        return $this;
    }
    /**
     * Get the value of name
     */ 
    public function getIsRead()
    {
        return $this->is_read;
    }

    /**
     * Set the value of isRead
     *
     * @return  self
     */ 
    public function setIsRead($is_read)
    {
        $this->is_read = $is_read;

        return $this;
    }
   
}