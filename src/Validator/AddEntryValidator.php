<?php


namespace BlackScorp\GuestBook\Validator;

use BlackScorp\GuestBook\MessageStream\AddEntryMessageStream;

abstract class AddEntryValidator extends BaseValidator
{
    /**
     * @var AddEntryMessageStream
     */
    protected $messageStream = null;

    public function setMessageStream(AddEntryMessageStream $messageStream)
    {
        $this->messageStream = $messageStream;
    }
}