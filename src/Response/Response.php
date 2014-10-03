<?php namespace GuestBook\Response;


interface Response {
    public function failed();
    public function errors();
    public function fail();
    public function setErrors(array $errors);
} 