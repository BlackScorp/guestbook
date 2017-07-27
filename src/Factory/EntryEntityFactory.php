<?php

namespace BlackScorp\GuestBook\Factory;


use BlackScorp\GuestBook\Entity\EntryEntity;

interface EntryEntityFactory
{
    /**
     * @param $author
     * @param $text
     * @return EntryEntity
     */
    public function create($author, $text);
}