<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Entity\VehicleBooking;
use App\Exception\UserHasAlreadyAReservationException;
use App\Exception\VehicleAlreadyBookedException;
use App\Form\VehicleBookingType;
use App\Form\VehicleType;
use App\Repository\VehicleRepository;
use App\ResponseModel\VehicleModel;
use App\Service\BookVehicleService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted('ROLE_USER')]
final class VehicleController extends AbstractController
{
    public function __construct(
        private VehicleRepository $vehicleRepository,
        private BookVehicleService $bookVehicleService,
        private TranslatorInterface $translator,
        private LoggerInterface $logger,
    ) {
    }

    #[Route('/vehicle', name: 'app_vehicle_list')]
    public function index(
        #[MapQueryString] PaginationDto $paginationDto,
    ): Response {
        $hash = md5('test');

        $paginatedVehicles = $this->vehicleRepository->findAllPaginated(
            $paginationDto->page,
            $paginationDto->limit,
            $paginationDto->filters?->label
        );

        $this->logger->info('Listing des véhicules', [
            'filter' => $paginationDto->filters,
            'userId' => $this->getUser()->getUserIdentifier(),
            'context_name' => 'app_vehicle_list',
            'id' => $hash,
        ]
        );

        $this->logger->error('error sur le listing des véhicules', [
            'userId' => $this->getUser()->getUserIdentifier(),
            'context_name' => 'app_vehicle_list',
            'id' => $hash,
        ]);

        return $this->render('vehicle/list.html.twig', [
            'vehicles' => $paginatedVehicles->getIterator(),
            'totalCount' => $paginatedVehicles->count(),
        ]);
    }

    #[Route('/api/vehicles', name: 'api_vehicle_list')]
    public function apiVehiclesList(): JsonResponse
    {
        $vehicles = $this->vehicleRepository->findAll();
        $vehicleResponses = [];
        foreach ($vehicles as $vehicle) {
            $vehicleResponses[] = VehicleModel::fromVehicle($vehicle);
        }

        return $this->json($vehicleResponses);
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

            try {
                $this->bookVehicleService->bookVehicle($bookingVehicle);
            } catch (VehicleAlreadyBookedException|UserHasAlreadyAReservationException $exception) {
                $this->addFlash('error', $this->translator->trans($exception->getMessage()));

                return $this->render('vehicle/book-vehicle.html.twig', [
                    'vehicleBookingForm' => $form,
                ]);
            }

            return $this->redirectToRoute('app_home');
        }

        return $this->render('vehicle/book-vehicle.html.twig', [
            'vehicleBookingForm' => $form,
        ]);
    }
}
