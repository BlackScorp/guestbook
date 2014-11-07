<?php namespace GuestBook\Validator;


abstract class Validator {
    private $errors = array();
    public function isValid(){
        $this->validate();
        return count($this->errors) === 0;
    }
    protected function addErrorMessage($errorMessage){
        $this->errors[] = $errorMessage;
    }
    public function getErrors(){
        return $this->errors;
    }
    abstract public function validate();
} 