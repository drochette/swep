<?php

declare(strict_types=1);

namespace App\Exception;

final class UserHasAlreadyAReservationException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('user.reservation.in_progress');
    }
}
