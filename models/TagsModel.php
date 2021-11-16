<?php

class TagsModel
{
    private string $tag;

    public function __construct($tag)
    {
        $this->tag = $tag;
    }

    public function getTag(): string
    {
        return $this->tag;
    }
}
