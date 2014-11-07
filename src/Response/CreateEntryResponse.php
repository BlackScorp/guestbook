<?php namespace GuestBook\Response;

use GuestBook\Request\CreateEntryRequest;

interface CreateEntryResponse extends  Response {
    public function setRequestData(CreateEntryRequest $request);
} 