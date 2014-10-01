<?php namespace GuestBook\Mock\Repository;

use GuestBook\Entity\EntryEntity;
use GuestBook\Repository\EntryRepository;

class MockEntryRepository implements EntryRepository{
    /**
     * @var EntryEntity[]
     */
    private $entries = array();
    public function getUniqueId()
    {
        return 1;
    }

    public function create($entryId, $authorName, $authorEmail, $content)
    {
        return new EntryEntity($entryId,$authorName,$authorEmail,$content);
    }

    public function add(EntryEntity $entity)
    {
        $this->entries[$entity->getEntryId()] = $entity;
    }

} 