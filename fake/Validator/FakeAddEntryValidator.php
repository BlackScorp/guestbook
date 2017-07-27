<?php


namespace BlackScorp\GuestBook\Fake\Validator;


use BlackScorp\GuestBook\Validator\AddEntryValidator;

class FakeAddEntryValidator extends AddEntryValidator
{
    protected function validate()
    {
        return false;
    }


}