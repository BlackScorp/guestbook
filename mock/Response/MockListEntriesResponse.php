<?php namespace GuestBook\Mock\Response;

use GuestBook\Response\ListEntriesResponse;
use GuestBook\View\EntryView;

class MockListEntriesResponse implements ListEntriesResponse{
    public  $entries;
    public function addEntry(EntryView $entry)
    {
       $this->entries[]=$entry;
    }

} 