<?php namespace GuestBook\Mock\Response;

use GuestBook\ErrorTrait;
use GuestBook\Request\CreateEntryRequest;
use GuestBook\Response\CreateEntryResponse;

class MockCreateEntryResponse implements CreateEntryResponse{
    use ErrorTrait;
    public $authorName = '';
    public $authorEmail = '';
    public $content = '';


    public function setRequestData(CreateEntryRequest $request)
    {
        $this->authorEmail = $request->getAuthorEmail();
        $this->authorName = $request->getAuthorName();
        $this->content = $request->getContent();
    }


} 