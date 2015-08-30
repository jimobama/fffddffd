<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserModel
 *
 * @author obaro
 */
class UserModel extends IModel {
    //put your code here
    
    private $user=null;
    private $db=null;
    function __construct(User $user=null,Database $db=null) {
        $this->user=$user;
        $this->db = $db;
        
    }
   function GetUser(User $user=null)
    {
       $row=null;
        if($user ==null){
            $user = $this->user;
        }
        
        if($this->db !=null)
        {
            $query = "select firstname,lastname,date_registered,id,active,email,phone"
                    . " from  tbl_user where( (email=:email or phone=:phone) or id=:id)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":email", addslashes(strtolower(trim($user->email))));
            $stmt->bindValue(":phone", $user->phone);
            $stmt->bindValue(":id", $user->id);
            
           if(!$stmt->execute())
            {
                print_r($stmt->errorInfo());
               
            }else
             { 
              if($stmt->rowCount()>0){
                    $row= $stmt->fetch(PDO::FETCH_ASSOC);
                    
              }
            }
        }
        
       return $row; 
    }
    function IsAccountExists()
    {
         $okay=false;
         if($this->db !=null)
         {
             $query="Select *from tbl_user where (email=:email or id=:id or phone=:phone) and password=:password ";
             $stmt = $this->db->prepare($query);
             $stmt->bindValue(":email", addslashes(strtolower(trim($this->user->email))));
             $stmt->bindValue(":password", sha1($this->user->getPassword()));
              $stmt->bindValue(":id", sha1($this->user->id));
              $stmt->bindValue(":phone", sha1($this->user->phone));
            if(!$stmt->execute())
            {
                print_r($stmt->errorInfo());
               
            }else
             { 
              if($stmt->rowCount()>0){
                    $okay=true;
              }
            }
         }
         
      return $okay;
    }
    
    
    
    function IsExists()
    {
         $okay=false;
         if($this->db !=null)
         {
             $query="Select *from tbl_user where (email=:email or id=:id or phone=:phone)";
             $stmt = $this->db->prepare($query);
             $stmt->bindValue(":email", addslashes(strtolower(trim($this->user->email))));            
              $stmt->bindValue(":id", sha1($this->user->id));
              $stmt->bindValue(":phone", sha1($this->user->phone));
            if(!$stmt->execute())
            {
                print_r($stmt->errorInfo());
               
            }else
             { 
              if($stmt->rowCount()>0){
                    $okay=true;
              }
            }
         }
         
      return $okay;
    }

    public function Create() {
        $status=false;
        
        if($this->db !=null)
        {
            
            //create db values generically
           $query = "Insert into tbl_user (email,firstname,lastname,gender,id,phone,password,active)"
                   . "values(:email,:firstname,:lastname,:gender,:id,:phone,:password,:active)";
           
           $stmt= $this->db->prepare($query);
           
           $stmt->bindValue(":email",addslashes(strtolower(trim($this->user->email))));
           $stmt->bindValue(":firstname",$this->user->firstname);
           $stmt->bindValue(":lastname",$this->user->lastname);
           $stmt->bindValue(":gender",$this->user->gender);
           $stmt->bindValue(":id",$this->user->id);
           $stmt->bindValue(":phone",$this->user->phone);
           $stmt->bindValue(":password",sha1($this->user->getPassword()));
           $stmt->bindValue(":active",0);
           $status=  $stmt->execute();
           
           if(!$status){
               $json= json_encode($stmt->errorInfo());
               $this->setError($json);
           }
         
        }
        
        return $status;        
    }

    public function Delete(Object $object = null) {
        if($this->db !=null )
        {
            if($object !=null)
            {
                $query="delete from tbl_user where email =:email or phone = :phone or id= :id";
                $stmt= $this->db->prepare($query);
                $stmt->bindValue(":email",addslashes(strtolower(trim($object->email))));
                $stmt->bindValue(":phone",$object->phone);
                $stmt->bindValue(":id",$object->id);
                $status=  $stmt->execute();
                //check if there is an error
                if(!$status){
                    $json= json_encode($stmt->errorInfo());
                    $this->setError($json);
                }
            }
        }
    }

    public function Search(Object $object = null) {
        
        $response =new ArrayIterator();
        if($object !=null && $this->db !=null)
        {
            $query ="select firstname,lastname,date_registered,id,active,email,phone "
                    . "from tbl_user where email =:email or phone = :phone or id= :id ";
            $stmt= $this->db->prepare($query);
                $stmt->bindValue(":email",addslashes(strtolower(trim($object->email))));
                $stmt->bindValue(":phone",$object->phone);
                $stmt->bindValue(":id",$object->id);
                $status=  $stmt->execute();
                //check if there is an error
                if(!$status){
                    $json= json_encode($stmt->errorInfo());
                    $this->setError($json);
                }
              
              while($row= $stmt->fetch(PDO::FETCH_ASSOC))
                  {
                     $response->append($row);
                  }
                
               
        }
        
        return $response;
    }

    public function Update($id, Object $object = null) {
        
        $okay=false;
        if($this->db !=NULL)
        {
            $query="Update tbl_user set email=:email,phone=:phone, firstname=:firstname"
                    . ", lastname=:lastname, gender=:gender "
                    . "where id=:id";
            
            //update
            $stmt= $this->db->prepare($query);
            $stmt->bindValue(":email",addslashes(strtolower(trim($object->email))));
            $stmt->bindValue(":firstname",$object->firstname);
            $stmt->bindValue(":lastname",$object->lastname);
            $stmt->bindValue(":gender",$object->gender);
            $stmt->bindValue(":id",$id);
            $stmt->bindValue(":phone",$object->phone);          
            $stmt->bindValue(":active",0);
            
            $status=  $stmt->execute();
                //check if there is an error
                if(!$status){
                    $json= json_encode($stmt->errorInfo());
                    $this->setError($json);
                }  else {
                    $okay=true;
                }
                
                
        }
        
        return $okay;
    }

}
