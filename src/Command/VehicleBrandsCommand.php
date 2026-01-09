<?php

namespace App\Command;

use App\HttpClient\NhtsaApiClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:vehicle-brands',
    description: 'List all vehicles brands',
)]
class VehicleBrandsCommand extends Command
{
    public function __construct(private NhtsaApiClient $httpClient)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $brands = $this->httpClient->getBrands(2010);

        foreach ($brands as $vehicleBrand) {
            $io->text($vehicleBrand->getName());
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
