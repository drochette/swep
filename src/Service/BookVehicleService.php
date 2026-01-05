<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\VehicleBooking;
use App\Exception\VehicleAlreadyBookedException;
use App\Repository\VehicleBookingRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class BookVehicleService
{
    public function __construct(
        private MailerInterface $mailer,
        private readonly string $fromEmail,
        private readonly VehicleBookingRepository $vehicleBookingRepository,
    ) {
    }

    public function bookVehicle(VehicleBooking $vehicleBooking)
    {
        $this->ensureThatVehicleIsAvailable($vehicleBooking);
        $this->ensureUserHasAOnlyOneVehicleReservation($vehicleBooking);

        $this->vehicleBookingRepository->save($vehicleBooking);

        $this->sendConfirmationEmail($vehicleBooking);
    }

    /**
     * @throws VehicleAlreadyBookedException
     */
    private function ensureThatVehicleIsAvailable(VehicleBooking $vehicleBooking): ?VehicleAlreadyBookedException
    {
        $isBooked = $this->vehicleBookingRepository->isVehicleBooked(
            $vehicleBooking->getVehicle(),
            $vehicleBooking->getStartAt(),
            $vehicleBooking->getEndAt()
        );

        if ($isBooked) {
            throw new VehicleAlreadyBookedException();
        }

        return null;
    }

    private function ensureUserHasAOnlyOneVehicleReservation(VehicleBooking $vehicleBooking): ?VehicleAlreadyBookedException
    {
        $hasReservation = $this->vehicleBookingRepository->isUserHasAlreadyAReservation(
            $vehicleBooking->getBookedBy(),
            $vehicleBooking->getStartAt(),
            $vehicleBooking->getEndAt()
        );

        if ($hasReservation) {
            throw new VehicleAlreadyBookedException();
        }

        return null;
    }

    private function sendConfirmationEmail(VehicleBooking $vehicleBooking): void
    {
        $bookedBy = $vehicleBooking->getBookedBy();
        $vehicle = $vehicleBooking->getVehicle();

        $email = (new Email())
            ->from($this->fromEmail)
            ->to($bookedBy->getEmail())
            ->subject(
                sprintf(
                    'Reservation pris du %s au %s',
                    $vehicleBooking->getStartAt()->format('d/m/Y'),
                    $vehicleBooking->getEndAt()->format('d/m/Y')
                ))
            ->html(
                sprintf(
                    '<p>La réservation a bien été prise en compte pour le véhicule %s immatriculé %s!</p>',
                    $vehicle->getLabel(),
                    $vehicle->getRegistrationNumber()
                )
            );

        $this->mailer->send($email);
    }
}
