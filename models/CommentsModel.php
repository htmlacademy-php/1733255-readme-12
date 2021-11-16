<?php

class CommentsModel
{
    private string $content;
    private string $date;
    private string $author;
    private string $avatar;

    public function __construct($content, $date, $author, $avatar)
    {
        $this->content = $content;
        $this->date = $date;
        $this->author = $author;
        $this->avatar = $avatar;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }
}
