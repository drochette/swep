<?php

declare(strict_types=1);

namespace App\Email;

use App\Entity\VehicleBooking;
use Symfony\Component\Mime\Email;

final class BookingVehicleConfirmationEmail extends Email
{
    public function __construct(VehicleBooking $vehicleBooking, string $fromEmail)
    {
        $bookedBy = $vehicleBooking->getBookedBy();
        $vehicle = $vehicleBooking->getVehicle();

        parent::__construct();

        $this->from($fromEmail);
        $this->to($bookedBy->getEmail());
        $this->subject(
            sprintf(
                'Reservation pris du %s au %s',
                $vehicleBooking->getStartAt()->format('d/m/Y'),
                $vehicleBooking->getEndAt()->format('d/m/Y')
            )
        );
        $this->html(
            sprintf(
                '<p>La réservation a bien été prise en compte pour le véhicule %s immatriculé %s!</p>',
                $vehicle->getLabel(),
                $vehicle->getRegistrationNumber()
            )
        );
    }
}
