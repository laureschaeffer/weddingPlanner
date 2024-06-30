<?php


namespace App\Command;

use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteOldProjectsCommand extends Command
{
    protected static $defaultName = 'app:delete-old-projects';
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
        $this->setDescription('Supprime les réservations plus anciennes que X temps.');
    }

    protected function executeCmd(InputInterface $input, OutputInterface $output)
    {
        $oldProjects = $this->projectRepository->findOldProjects();

        foreach ($oldProjects as $project) {
            $this->entityManager->remove($project);
        }

        $this->entityManager->flush();

        $output->writeln('Les réservations datant de plus de 2 ans ont été supprimées.');

        return Command::SUCCESS;
    }
}
