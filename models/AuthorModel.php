<?php

class AuthorModel
{
    private string $date;
    private string $userName;
    private string $avatar;
    private string $postsTotal;
    private string $subscribersTotal;

    public function __construct($date, $userName, $avatar, $postsTotal, $subscribersTotal) {
        $this->date = $date;
        $this->userName = $userName;
        $this->avatar = $avatar;
        $this->postsTotal = $postsTotal;
        $this->subscribersTotal = $subscribersTotal;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function getPostsTotal(): string
    {
        return $this->postsTotal;
    }

    public function getSubscribersTotal(): string
    {
        return $this->subscribersTotal;
    }
}
