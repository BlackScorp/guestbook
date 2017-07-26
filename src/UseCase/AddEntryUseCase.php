<?php


namespace BlackScorp\GuestBook\UseCase;


use BlackScorp\GuestBook\Validator\AddEntryValidator;
use BlackScorp\GuestBook\MessageStream\AddEntryMessageStream;

final class AddEntryUseCase
{
    /**
     * @var AddEntryValidator
     */
    private $validator = null;

    /**
     * AddEntryUseCase constructor.
     * @param AddEntryValidator $validator
     */
    public function __construct(AddEntryValidator $validator)
    {
        $this->validator = $validator;
    }


    public function process(AddEntryMessageStream $messageStream)
    {
    }
}