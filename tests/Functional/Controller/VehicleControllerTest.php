<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Repository\CampusRepository;
use App\Repository\UserRepository;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class VehicleControllerTest extends WebTestCase
{
    public function testListVehiclesIsOk(): void
    {
        $client = static::createClient();

        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'rochette.damien@gmail.com']);

        $client->loginUser($user);

        $crawler = $client->request('GET', '/vehicle');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des véhicules');
    }

    public function testAddANewVehicle(): void
    {
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'rochette.damien@gmail.com']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/vehicle/add');

        $pathImg = __DIR__.'/../../resources/images/vehicles/porsche.jpg';
        $image = new UploadedFile($pathImg, 'porsche.jpg', 'image/jpeg', null, true);

        $form = $crawler->selectButton('Ajouter')->form([
            'vehicle[label]' => 'Porsche 911',
            'vehicle[campus]' => 2,
            'vehicle[registrationNumber]' => 'XX123YYAA',
        ]);
        $form['vehicle[image]'] = $image;

        $client->submit($form);

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Porsche 911');
        $this->assertSelectorTextContains('.alert.alert-success', 'Vehicle ajouté');

        $vehicle = static::getContainer()->get(VehicleRepository::class)->findOneBy(['label' => 'Porsche 911']);
        $this->assertNotNull($vehicle);
        $this->assertEquals('XX123YYAA', $vehicle->getRegistrationNumber());
    }

    public function testAddVehicleAlreadyExists(): void
    {
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'rochette.damien@gmail.com']);
        $client->loginUser($user);

        /** @var VehicleRepository $vehicleRepository */
        $vehicleRepository = static::getContainer()->get(VehicleRepository::class);
        $vehicle = $vehicleRepository->findOneBy(['label' => 'Renault R5']);
        $campus = static::getContainer()->get(CampusRepository::class)->findOneBy(['label' => 'Blois']);

        if (!$vehicle) {
            $this->markTestSkipped('Aucun véhicule trouvé pour tester le doublon.');
        }

        $crawler = $client->request('GET', '/vehicle/add');

        $pathImg = __DIR__.'/../../resources/images/vehicles/porsche.jpg';
        $image = new UploadedFile($pathImg, 'porsche.jpg', 'image/jpeg', null, true);

        $form = $crawler->selectButton('Ajouter')->form([
            'vehicle[label]' => $vehicle->getLabel(),
            'vehicle[campus]' => $campus->getId(),
            'vehicle[registrationNumber]' => 'XX123YY',
        ]);
        $form['vehicle[image]'] = $image;

        $client->submit($form);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.alert.alert-danger');
        $this->assertSelectorTextContains('.alert.alert-danger', 'Vehicle existe déjà');
    }

    public function testItHandlesErrorOnInvalidImage(): void
    {
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'rochette.damien@gmail.com']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/vehicle/add');

        $pathImg = __DIR__.'/../../resources/images/vehicles/file.txt';
        $image = new UploadedFile($pathImg, 'file.txt', 'image/jpeg', null, true);

        $form = $crawler->selectButton('Ajouter')->form([
            'vehicle[label]' => 'Porsche 911',
            'vehicle[campus]' => 2,
            'vehicle[registrationNumber]' => 'XX123YYAA',
        ]);
        $form['vehicle[image]'] = $image;

        $client->submit($form);

        $this->assertSelectorTextContains('#vehicle_image_error1', 'Le type du fichier est invalide ("text/plain"). Les types autorisés sont "image/jpeg", "image/png".');
    }
}
