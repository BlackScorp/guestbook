<?php namespace GuestBook\Validator;

abstract class CreateEntryValidator extends Validator{
    public $authorName = '';
    public $authorEmail = '';
    public $content = '';
} 