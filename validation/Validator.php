<?php

class Validator
{

    protected string $message = '';

    public function getMessage(): string
    {
        return $this->message;
    }
    protected function setMessage($message) {
        $this->message = $message;
    }
}
