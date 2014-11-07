<?php
namespace GuestBook;

interface ErrorInterface {
    public function hasErrors();
    public function setErrors(array $errors);
    public function getErrors();
    public function appendError($message);
} 