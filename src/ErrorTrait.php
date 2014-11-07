<?php
namespace GuestBook;


trait ErrorTrait{
    private $errors = array();
    public function hasErrors(){
        return count($this->errors) > 0;
    }
    public function setErrors(array $errors){
        $this->errors = $errors;
    }
    public function getErrors(){
        return $this->errors;
    }
    public function appendError($message){
        $this->errors[]=$message;
    }
} 