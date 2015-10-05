<?php

namespace BlackScorp\GuestBook\Entity;


class EntryEntity {
    private $author = '';
    private $text = '';

    /**
     * EntryEntity constructor.
     * @param string $author
     * @param string $text
     */
    public function __construct($author, $text)
    {
        $this->author = $author;
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }


}