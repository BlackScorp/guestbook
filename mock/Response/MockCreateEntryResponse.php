<?php namespace GuestBook\Mock\Response;

use GuestBook\Response\CreateEntryResponse;

class MockCreateEntryResponse implements CreateEntryResponse{
    private $failed = false;
    public function failed()
    {
        return $this->failed;
    }

} 