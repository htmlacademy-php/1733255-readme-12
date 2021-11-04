<?php

abstract class Validator
{
    private string $error;

    protected function setError($error) {
        $this->error = $error;
    }

    public function getError(): string
    {
        return $this->error;
    }

    abstract public function validate($value): bool;
}
