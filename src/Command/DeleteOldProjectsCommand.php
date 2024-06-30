<?php


namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// DOC: the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:delete-old-projects')]
class DeleteOldProjectsCommand extends Command
{

    private $projectRepository;
    private $entityManager;

    public function __construct(ProjectRepository $projectRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->projectRepository = $projectRepository;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setDescription('Supprime les projets plus anciens que 2 ans.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $oldProjects = $this->projectRepository->findOldProjects();

        foreach ($oldProjects as $project) {
            $this->entityManager->remove($project);
        }

        $this->entityManager->flush();

        $output->writeln('Les projets datant de plus de 2 ans ont été supprimées.');

        return Command::SUCCESS;
    }
}
