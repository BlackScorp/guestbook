<?php namespace GuestBook\Mock\Response;

use GuestBook\Request\CreateEntryRequest;
use GuestBook\Response\CreateEntryResponse;


class MockCreateEntryResponse implements CreateEntryResponse{

    public $authorName = '';
    public $authorEmail = '';
    public $content = '';


    public function setRequestData(CreateEntryRequest $request)
    {
        $this->authorEmail = $request->getAuthorEmail();
        $this->authorName = $request->getAuthorName();
        $this->content = $request->getContent();
    }

    private $failed = false;
    private $errors = array();

    public function failed(){
        return $this->failed;
    }
    public function errors(){
        return $this->errors;
    }
    public function fail(){
        $this->failed = true;
    }
    public function setErrors(array $errors){
        $this->errors = $errors;
    }
} 