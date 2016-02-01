<?php
namespace BlackScorp\GuestBook\Fake\Request;

use BlackScorp\GuestBook\Request\ViewEntriesRequest;

class FakeViewEntriesRequest implements ViewEntriesRequest
{
    private $offset = 0;
    private $limit = 0;

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
}