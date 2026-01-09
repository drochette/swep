<?php

namespace App\Security\Voter;

use App\Entity\VehicleBooking;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class VehicleBookingVoter extends Voter
{
    public const CAN_DELETE_VEHICLE_BOOKING = 'CAN_DELETE_VEHICLE_BOOKING';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::CAN_DELETE_VEHICLE_BOOKING]) && $subject instanceof VehicleBooking;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var VehicleBooking $bookingVehicle */
        $bookingVehicle = $subject;

        if (self::CAN_DELETE_VEHICLE_BOOKING === $attribute) {
            return $this->canBeDeleted($user, $bookingVehicle);
        }

        return false;
    }

    private function canBeDeleted(UserInterface $user, VehicleBooking $bookingVehicle): bool
    {
        if ($user === $bookingVehicle->getBookedBy()) {
            return true;
        }

        $vote->addReason('User is not the owner of the vehicle booking.');

        return false;
    }
}
