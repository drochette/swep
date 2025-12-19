<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class VehicleController extends AbstractController
{

    public function __construct(private VehicleRepository $vehicleRepository)
    {
    }

    #[Route('/vehicle', name: 'app_vehicle_list')]
    public function index(): Response
    {
        return $this->render('vehicle/list.html.twig', [
            'vehicles' => $this->vehicleRepository->findAll(),
            'htmlcontent' => '<b>test</b>',
        ]);
    }


    #[Route('/vehicle/{id}', name: 'app_vehicle_show', requirements: ['id' => Requirement::DIGITS])]
    public function show(int $id): Response
    {
        $vehicle = $this->vehicleRepository->findOneBy(['id' => $id]);

        return $this->render('vehicle/show.html.twig', [
            'vehicle' => $vehicle,
        ]);
    }
}
