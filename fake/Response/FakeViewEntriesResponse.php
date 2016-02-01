<?php
namespace BlackScorp\GuestBook\Fake\Response;

use BlackScorp\GuestBook\Response\ViewEntriesResponse;

class FakeViewEntriesResponse implements ViewEntriesResponse
{
    public $entries = [];
}