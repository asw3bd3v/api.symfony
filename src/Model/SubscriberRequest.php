<?php

namespace App\Model;

class SubscriberRequest
{
    private string $email;
    private bool $agreed;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isAgreed(): bool
    {
        return $this->agreed;
    }

    public function setAgreed(bool $agreed): static
    {
        $this->agreed = $agreed;

        return $this;
    }
}
