<?php


namespace App\Command;

use App\Repository\QuotationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// DOC: the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:delete-old-quotations')]
class DeleteOldQuotationsCommand extends Command
{

    private $quotationRepository;
    private $entityManager;

    public function __construct(QuotationRepository $quotationRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->quotationRepository = $quotationRepository;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->setDescription('Supprime les devis refusés de plus de 3 mois.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $oldQuotations = $this->quotationRepository->findOldQuotations();

        foreach ($oldQuotations as $quotation) {
            $this->entityManager->remove($quotation);
        }

        $this->entityManager->flush();

        $output->writeln('Les devis refusés de plus de 3 mois ont été supprimés.');

        return Command::SUCCESS;
    }
}
