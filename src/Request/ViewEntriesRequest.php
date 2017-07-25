<?php
namespace BlackScorp\GuestBook\Request;

interface ViewEntriesRequest
{

    public function getOffset();

    public function getLimit();
}