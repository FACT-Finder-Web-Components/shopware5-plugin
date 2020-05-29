<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Api;

class Credentials
{
    /** @var string */
    private $username;

    /** @var string */
    private $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function __toString(): string
    {
        return 'Basic ' . base64_encode("{$this->username}:{$this->password}");
    }
}
