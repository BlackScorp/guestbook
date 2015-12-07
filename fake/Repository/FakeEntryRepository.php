<?php

namespace BlackScorp\GuestBook\Fake\Repository;

use BlackScorp\GuestBook\Repository\EntryRepository;

class FakeEntryRepository implements EntryRepository{
    private $entries = [];

    public function __construct(array $entries = [])
    {
        $this->entries = $entries;
    }

    public function findAllPaginated($offset, $limit)
    {

        return array_splice($this->entries,$offset,$limit);
    }
}