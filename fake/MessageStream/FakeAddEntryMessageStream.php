<?php

namespace BlackScorp\GuestBook\Fake\MessageStream;


use BlackScorp\GuestBook\MessageStream\AddEntryMessageStream;

class FakeAddEntryMessageStream implements AddEntryMessageStream
{
    public $text = '';
    public $author = '';
    public $visibleAuthorIsEmptyMessage = false;

    /**
     * FakeAddEntryMessageStream constructor.
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
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

}