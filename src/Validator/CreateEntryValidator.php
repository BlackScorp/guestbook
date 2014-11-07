<?php namespace GuestBook\Validator;

use GuestBook\Request\CreateEntryRequest;

abstract class CreateEntryValidator extends Validator{
    public $authorName = '';
    public $authorEmail = '';
    public $content = '';
    public function setRequestData(CreateEntryRequest $request){
        $this->authorName = $request->getAuthorName();
        $this->authorEmail = $request->getAuthorEmail();
        $this->content = $request->getContent();
    }
} 