<?php

class ContentTypeModel
{
    private string $id;
    private string $type;
    private string $title;

    public function __construct($id, $type, $title) {
        $this->id = $id;
        $this->type = $type;
        $this->title = $title;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
