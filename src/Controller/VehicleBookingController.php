<?php

namespace App\Controller;

use App\Repository\VehicleBookingRepository;
use App\Service\BookVehicleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class VehicleBookingController extends AbstractController
{
    public function __construct(private VehicleBookingRepository $vehicleBookingRepository, private BookVehicleService $bookVehicleService)
    {
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/booked-vehicles', name: 'app_user_booked_vehicles')]
    public function index(): Response
    {
        $connectedUser = $this->getUser();

        $bookings = $this->vehicleBookingRepository->findBy(['bookedBy' => $connectedUser], ['endAt' => 'ASC']);

        return $this->render('vehicle_booking/user_vehicles_booked.html.twig', [
            'bookings' => $bookings,
        ]);
    }

    #[Route('/booked-vehicles/{id}/delete', name: 'app_delete_user_booked_vehicle')]
    public function deleteBooking(int $id): Response
    {
        $vehicleBooking = $this->vehicleBookingRepository->find($id);

        if (!$this->isGranted(attribute: 'CAN_DELETE_VEHICLE_BOOKING', subject: $vehicleBooking)) {
            $this->addFlash('error', 'Vous n\'avez pas le droit');

            return $this->redirectToRoute('app_user_booked_vehicles');
        }

        $this->addFlash('success', 'La réservation a été supprimée');

        $this->bookVehicleService->delete($vehicleBooking);

        return $this->redirectToRoute('app_user_booked_vehicles');
    }
}
