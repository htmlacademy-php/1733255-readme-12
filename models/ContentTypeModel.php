<?php

class ContentTypeModel
{
    public string $id;
    public string $type;
    public string $title;

    public function __construct($id, $type, $title) {
        $this->id = $id;
        $this->type = $type;
        $this->title = $title;
    }

    public function createArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
        ];
    }
}
