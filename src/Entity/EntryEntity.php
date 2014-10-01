<?php namespace GuestBook\Entity;

class EntryEntity {
    private $entryId = 0;
    private $authorName = '';
    private $authorEmail = '';
    private $content = '';

    public function __construct($entryId, $authorName, $authorEmail, $content)
    {
        $this->entryId     = $entryId;
        $this->authorName  = $authorName;
        $this->authorEmail = $authorEmail;
        $this->content     = $content;
    }

    /**
     * @return string
     */
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    /**
     * @return string
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getEntryId()
    {
        return $this->entryId;
    }


} 