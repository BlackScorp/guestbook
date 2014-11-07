<?php namespace GuestBook\Request;

interface CreateEntryRequest {

    /**
     * @return string
     */
    public function getAuthorEmail();

    /**
     * @return string
     */
    public function getAuthorName();

    /**
     * @return string
     */
    public function getContent();
} 