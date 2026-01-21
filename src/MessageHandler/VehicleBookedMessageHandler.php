<?php

namespace App\MessageHandler;

use App\Email\BookingVehicleConfirmationEmail;
use App\Message\VehicleBookedMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;

#[AsMessageHandler]
final class VehicleBookedMessageHandler
{
    public function __construct(
        private LoggerInterface $logger,
        private readonly MailerInterface $mailer,
        private readonly TexterInterface $texter,
        private readonly string $fromEmail,
    ) {
    }

    public function __invoke(VehicleBookedMessage $message): void
    {
        $vehicleBooking = $message->getVehicleBooking();

        $this->logger->info('VEHICULE BOOKED MESSAGE', [
            'bookedBy' => $vehicleBooking->getBookedBy()->getEmail(),
            'vehicleId' => $vehicleBooking->getVehicle()->getId(),
        ]);

        $this->mailer->send(new BookingVehicleConfirmationEmail($vehicleBooking, $this->fromEmail));
        $this->texter->send(new SmsMessage(
            // the phone number to send the SMS message to
            '+1411111111',
            'A new login was detected!',
            '+1422222222',
        ));
    }
}
