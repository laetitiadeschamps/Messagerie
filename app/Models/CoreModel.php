<?php

namespace App\Models;


abstract class CoreModel {
    /**
     * @var int
     */
    public $id;
   
    public $created_at;
 
    public $updated_at;

    
    /**
     * Get the value of id
     *
     * @return  int
     */ 

    
    public function getId() 
    {
        return $this->id;
    }

    
    public function getCreatedAt() 
    {
        return $this->created_at;
    }

    
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
        return $this->updated_at;
    }
}
