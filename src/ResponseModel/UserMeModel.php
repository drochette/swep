<?php

declare(strict_types=1);

namespace App\ResponseModel;

final class UserMeModel
{
    public function __construct(private string $email)
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
