<?php namespace GuestBook\Request;

class CreateEntryRequest {
    private $authorName = '';
    private $authorEmail ='';
    private $content = '';

    public function __construct($authorName, $authorEmail, $content)
    {
        $this->authorName  = $authorName;
        $this->authorEmail = $authorEmail;
        $this->content     = $content;
    }

    /**
     * @return string
     */
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    /**
     * @return string
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
} 