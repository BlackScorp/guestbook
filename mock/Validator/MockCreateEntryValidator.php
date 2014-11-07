<?php namespace GuestBook\Mock\Validator;

use GuestBook\Validator\CreateEntryValidator;

class MockCreateEntryValidator extends CreateEntryValidator{

    public function validate()
    {
        if(empty($this->authorName)){
            $this->addErrorMessage('Authors Name is empty');
        }
        if(empty($this->authorEmail)){
            $this->addErrorMessage('Authors E-Mail is empty');
        }
        if(empty($this->content)){
            $this->addErrorMessage('Content is empty');
        }
    }

} 