<?php

namespace App\Message;

use App\Entity\VehicleBooking;

final class VehicleBookedMessage
{
    public function __construct(
        public readonly VehicleBooking $vehicleBooking,
    ) {
    }

    public function getVehicleBooking(): VehicleBooking
    {
        return $this->vehicleBooking;
    }
}
