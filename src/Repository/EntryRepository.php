<?php
namespace BlackScorp\GuestBook\Repository;

use BlackScorp\GuestBook\Entity\EntryEntity;

interface EntryRepository
{
    /**
     * @param $offset
     * @param $limit
     * @return EntryEntity[]|null
     */
    public function findAllPaginated($offset, $limit);

    public function add(EntryEntity $entry);
}