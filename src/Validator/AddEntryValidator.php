<?php


namespace BlackScorp\GuestBook\Validator;


use BlackScorp\GuestBook\MessageStream\AddEntryMessageStream;

abstract class AddEntryValidator
{
    /**
     * @var AddEntryMessageStream
     */
    protected $messageStream = null;
    public function setMessageStream(AddEntryMessageStream $messageStream)
    {
        $this->messageStream = $messageStream;
    }

    public function isValid()
    {
        return false === $this->validate();
    }
    abstract protected function validate();
}