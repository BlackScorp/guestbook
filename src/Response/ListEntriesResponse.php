<?php namespace GuestBook\Response;

use GuestBook\View\EntryView;

interface ListEntriesResponse {
    public function addEntry(EntryView $entry);
} 