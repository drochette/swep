<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Entity\VehicleBooking;
use App\Form\VehicleBookingType;
use App\Form\VehicleType;
use App\Repository\VehicleBookingRepository;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class VehicleController extends AbstractController
{
    public function __construct(
        private VehicleRepository $vehicleRepository,
        private VehicleBookingRepository $vehicleBookingRepository,
    ) {
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

    #[Route('/vehicle/add', name: 'app_vehicle_create')]
    public function addVehicle(Request $request): Response
    {
        $vehicle = new Vehicle();

        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $isExists = $this->vehicleRepository->findOneBy(['label' => $vehicle->getLabel()]);
            if ($isExists) {
                $this->addFlash('error', 'Vehicle existe déjà');

                return $this->render('vehicle/add.html.twig', [
                    'vehicleForm' => $form,
                ]);
            }

            /** @var UploadedFile $uploadFile */
            $uploadFile = $form->get('image')->getData();
            $fileName = uniqid().'.'.$uploadFile->getClientOriginalExtension();
            $targetDir = $this->getParameter('kernel.project_dir').'/public/images/vehicles';
            $uploadFile->move($targetDir, $fileName);
            $vehicle->setMainImage($fileName);
            $this->vehicleRepository->save($vehicle);

            $this->addFlash('success', 'Vehicle ajouté');

            return $this->redirectToRoute('app_vehicle_show', ['id' => $vehicle->getId()]);
        }

        return $this->render('vehicle/add.html.twig', [
            'vehicleForm' => $form,
        ]);
    }

    #[Route(
        path: '/vehicle/{id}/book',
        name: 'app_book_a_vehicle',
        requirements: ['id' => Requirement::DIGITS],
        methods: ['POST', 'GET'])
    ]
    public function bookAVehicle(?Vehicle $vehicle = null, Request $request)
    {
        $bookingVehicle = new VehicleBooking();

        if (null === $vehicle) {
            $this->addFlash('error', 'Le véhicule n\'existe pas');

            return $this->redirectToRoute('app_vehicle_list');
        }

        $form = $this->createForm(VehicleBookingType::class, $bookingVehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookingVehicle->setVehicle($vehicle);
            $bookingVehicle->setBookedBy($this->getUser());
            $this->vehicleBookingRepository->save($bookingVehicle);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('vehicle/book-vehicle.html.twig', [
            'vehicleBookingForm' => $form,
        ]);
    }
}
