<?php
namespace BlackScorp\GuestBook\Response;

use BlackScorp\GuestBook\View\EntryView;

interface ViewEntriesResponse
{
    public function addEntry(EntryView $entryView);
}