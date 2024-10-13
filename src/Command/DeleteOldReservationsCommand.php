<?php


namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// DOC: the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:delete-old-reservations')]
class DeleteOldReservationsCommand extends Command
{

    private $reservationRepository;
    private $entityManager;

    public function __construct(ReservationRepository $reservationRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->reservationRepository = $reservationRepository;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        // va également supprimer en cascade "booking" là où il y a la clé étrangère reservation_id
        $this->setDescription('Supprime les réservations dont la date de retrait est passée de plus de 2 ans.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $oldReservations = $this->reservationRepository->findOldReservations();

        foreach ($oldReservations as $reservation) {
            $this->entityManager->remove($reservation);
        }

        $this->entityManager->flush();

        $output->writeln('Les réservations datant de plus de 2 ans ont été supprimées.');

        return Command::SUCCESS;
    }
}
