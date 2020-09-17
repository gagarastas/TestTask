<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateAdminCommand extends Command
{
    private $passwordEncoderService;

    private $entityManager;

    public function __construct(UserPasswordEncoderInterface $passwordEncoderService, EntityManager $entityManager)
    {
        $this->passwordEncoderService = $passwordEncoderService;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('create-admin')
            ->setDescription('Create admin')
            ->addArgument('userName',  InputArgument::REQUIRED, 'Admin name')
            ->addArgument('password',  InputArgument::REQUIRED, 'Admin password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = new User();
        $user
            ->setUsername($input->getArgument('userName'))
            ->setRoles('ROLE_ADMIN')
            ->setPassword(
                $this->passwordEncoderService->encodePassword($user, $input->getArgument('password'))
            );

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (ORMException|OptimisticLockException $e) {
            $output->writeln('Failed to create admin user with error:' . $e->getMessage());

            return 0;
        }

        $output->writeln('Admin was successfully created');

        return 0;
    }
}

