<?php namespace GuestBook\Mock\Validator;

use GuestBook\ErrorTrait;
use GuestBook\Validator\CreateEntryValidator;

class MockCreateEntryValidator extends CreateEntryValidator{
    use ErrorTrait;
    public function validate()
    {
        if(empty($this->authorName)){
            $this->appendError('Authors Name is empty');
        }
        if(empty($this->authorEmail)){
            $this->appendError('Authors E-Mail is empty');
        }
        if(empty($this->content)){
            $this->appendError('Content is empty');
        }
        if(!empty($this->authorEmail) && !filter_var($this->authorEmail, FILTER_VALIDATE_EMAIL)){
            $this->appendError('Authors E-Mail is invalid');
        }
    }
} 