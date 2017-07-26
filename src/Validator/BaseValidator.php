<?php

namespace BlackScorp\GuestBook\Validator;

abstract class BaseValidator
{
    public function isValid(){
        return false ===  $this->validate();
    }
    abstract protected function validate();
}