<?php
namespace BlackScorp\GuestBook\Fake\Request;

use BlackScorp\GuestBook\Request\ViewEntriesRequest;

class FakeViewEntriesRequest implements ViewEntriesRequest
{
    public function getOffset()
    {
        return 0;
    }

    public function getLimit()
    {
        return 1;
    }

}