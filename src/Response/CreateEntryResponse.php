<?php namespace GuestBook\Response;

use GuestBook\Request\CreateEntryRequest;
use GuestBook\ErrorInterface;

interface CreateEntryResponse extends ErrorInterface {
    public function setRequestData(CreateEntryRequest $request);
} 