<?php namespace GuestBook\Validator;

use GuestBook\ErrorInterface;

abstract class Validator implements ErrorInterface{
    public function isValid(){
        $this->validate();
        return !$this->hasErrors();
    }
    abstract public function validate();
} 