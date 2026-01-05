<?php

declare(strict_types=1);

namespace App\ResponseModel;

use App\Entity\Vehicle;

final class VehicleModel
{
    public int $id;
    public string $label;

    private function __construct()
    {
    }

    public static function fromVehicle(Vehicle $vehicle): self
    {
        $self = new self();
        $self->id = $vehicle->getId();
        $self->label = $vehicle->getLabel();

        return $self;
    }
}
