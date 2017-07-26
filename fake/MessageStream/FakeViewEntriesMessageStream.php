<?php
namespace BlackScorp\GuestBook\Fake\MessageStream;

use BlackScorp\GuestBook\MessageStream\ViewEntriesMessageStream;
use BlackScorp\GuestBook\View\EntryView;


class FakeViewEntriesMessageStream implements ViewEntriesMessageStream
{
    private $offset = 0;
    private $limit = 0;
    public $entries = [];

    /**
     * FakeViewEntriesRequest constructor.
     * @param int $limit
     */
    public function __construct($limit)
    {
        $this->limit = $limit;
    }

    public function setPage($page = 1)
    {
        $this->offset = ($page - 1) * $this->limit;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getLimit()
    {
        return $this->limit;
    }


    public function addEntry(EntryView $entryView)
    {
        $this->entries[] = $entryView;
    }

}