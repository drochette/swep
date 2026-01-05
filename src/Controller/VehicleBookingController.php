<?php

namespace App\Controller;

use App\Repository\VehicleBookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class VehicleBookingController extends AbstractController
{
    public function __construct(private VehicleBookingRepository $vehicleBookingRepository)
    {
    }

    #[Route('/booked-vehicles', name: 'app_user_booked_vehicles')]
    public function index(): Response
    {
        $connectedUser = $this->getUser();

        $bookings = $this->vehicleBookingRepository->findBy(['bookedBy' => $connectedUser], ['endAt' => 'ASC']);

        return $this->render('vehicle_booking/user_vehicles_booked.html.twig', [
            'bookings' => $bookings,
        ]);
    }
}
