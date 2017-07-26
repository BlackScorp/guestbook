<?php
namespace BlackScorp\GuestBook\MessageStream;

use BlackScorp\GuestBook\View\EntryView;

interface ViewEntriesMessageStream
{
    public function getOffset();
    public function getLimit();
    public function addEntry(EntryView $entryView);
}