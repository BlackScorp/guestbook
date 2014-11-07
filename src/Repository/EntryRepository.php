<?php namespace GuestBook\Repository;

use GuestBook\Entity\EntryEntity;

interface EntryRepository {
    public function getUniqueId();

    /**
     * @param $entryId
     * @param $authorName
     * @param $authorEmail
     * @param $content
     *
     * @return EntryEntity
     */
    public function create($entryId,$authorName,$authorEmail,$content);
    public function add(EntryEntity $entity);
} 