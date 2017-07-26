<?php
namespace BlackScorp\GuestBook\MessageStream;


interface AddEntryMessageStream
{

    public function getAuthor();

    public function getText();
}