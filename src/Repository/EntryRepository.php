<?php
namespace BlackScorp\GuestBook\Repository;

interface EntryRepository {
    public function findAllPaginated($offset, $limit);
}