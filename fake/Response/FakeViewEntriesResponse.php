<?php
namespace BlackScorp\GuestBook\Fake\Response;

use BlackScorp\GuestBook\Response\ViewEntriesResponse;
use BlackScorp\GuestBook\View\EntryView;

class FakeViewEntriesResponse implements ViewEntriesResponse
{
    public $entries = [];

    public function addEntry(EntryView $entryView)
    {
        $this->entries[] = $entryView;
    }
}