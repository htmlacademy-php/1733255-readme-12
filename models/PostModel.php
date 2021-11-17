<?php

class PostModel
{
    private string $id;
    private string $title;
    private string $content;
    private string $author;
    private string $img;
    private string $video;
    private string $reference;
    private string $userId;
    private string $userName;
    private string $contentTypeId;
    private string $publicationDate;
    private string $views;
    private string $type;
    private string $likes;
    private string $commentsTotal;
    private string $avatar;
    private string $imageClass;

    public function __construct(
        $id,
        $title,
        $content,
        $author,
        $img,
        $video,
        $reference,
        $userId,
        $userName,
        $contentTypeId,
        $publicationDate,
        $views,
        $type,
        $likes,
        $commentsTotal,
        $avatar,
        $imageClass,
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->author = $author;
        $this->img = $img;
        $this->video = $video;
        $this->reference = $reference;
        $this->userId = $userId;
        $this->userName = $userName;
        $this->contentTypeId = $contentTypeId;
        $this->publicationDate = $publicationDate;
        $this->views = $views;
        $this->type = $type;
        $this->likes = $likes;
        $this->commentsTotal = $commentsTotal;
        $this->avatar = $avatar;
        $this->imageClass = $imageClass;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getImg(): string
    {
        return $this->img;
    }

    public function getVideo(): string
    {
        return $this->video;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getContentTypeId(): string
    {
        return $this->contentTypeId;
    }

    public function getPublicationDate(): string
    {
        return $this->publicationDate;
    }

    public function getViews(): string
    {
        return $this->views;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getLikes(): string
    {
        return $this->likes;
    }

    public function getCommentsTotal(): string
    {
        return $this->commentsTotal;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function getImageClass(): string
    {
        return $this->imageClass;
    }
}
