<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

/**
 * Un modèle représente une table (un entité) dans notre base
 * 
 * Un objet issu de cette classe réprésente un enregistrement dans cette table
 */
class Roles extends CoreModel {
    private $name;

    /**
     * Method to find a role based on its id
     * @param int
     * @return Roles
     */
    public static function find(int $id){
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `roles` WHERE `id` = ' . $id;
        $result = $pdo->query($sql); 
        $role = $result->fetchObject(self::class);
       
        return $role;
    }
    /**
     * Method to find all roles
     * @return Collection|Roles[]
     */
    public static function findAll() {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `roles`'; 
        $result = $pdo->query($sql); 
        $roles = $result->fetchAll(PDO::FETCH_CLASS, self::class);

        return $roles;
    }

   /**
     * Method to insert or update one role
     * @return bool
     */
    public function save() {

        $pdo = Database::getPDO();
        if($this->getId()) {
            $sql = "UPDATE `roles`
            SET `name` =:name, `updated_at` = NOW()
            WHERE id = " . $this->getId();
        } else {
            $sql = "INSERT INTO `roles` (name)
            VALUES (:name)
            ";
        }
        $pdoStatement = $pdo->prepare($sql);
       
        $insertedRows = $pdoStatement->execute([ 
            ':name'=>$this->name,  
        ]);

        if ($insertedRows > 0) {
            $this->id = $pdo->lastInsertId();
            return true;
        }
        return false;

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