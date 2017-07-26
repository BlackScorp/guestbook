<?php

namespace BlackScorp\GuestBook\Fake\MessageStream;


use BlackScorp\GuestBook\MessageStream\AddEntryMessageStream;

class FakeAddEntryMessageStream implements AddEntryMessageStream
{
    public $text = '';
    public $author = '';
    public $errors = [];

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
}