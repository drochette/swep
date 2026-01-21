<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\VehicleBooking;
use App\Exception\VehicleAlreadyBookedException;
use App\Message\VehicleBookedMessage;
use App\Repository\VehicleBookingRepository;
use Symfony\Component\Messenger\MessageBusInterface;

final class BookVehicleService
{
    public function __construct(
        private readonly VehicleBookingRepository $vehicleBookingRepository,
        private MessageBusInterface $messageBus,
    ) {
    }

    public function bookVehicle(VehicleBooking $vehicleBooking)
    {
        $this->ensureThatVehicleIsAvailable($vehicleBooking);
        $this->ensureUserHasAOnlyOneVehicleReservation($vehicleBooking);

        $this->vehicleBookingRepository->save($vehicleBooking);

        $vehicleBookedMessage = new VehicleBookedMessage($vehicleBooking);
        $this->messageBus->dispatch($vehicleBookedMessage);
    }

    public function delete(VehicleBooking $vehicleBooking): void
    {
        $this->vehicleBookingRepository->delete($vehicleBooking);
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
}
