<?php
namespace BlackScorp\GuestBook\Repository;

interface EntryRepository {
    public function findAll($offset,$limit);
}