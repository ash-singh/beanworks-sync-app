<?php

namespace App\Command;

use App\Document\User;
use App\Login\Password;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitializeDatabase extends ContainerAwareCommand
{
    /** @var DocumentManager */
    private $documentManager;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        DocumentManager $documentManager,
        LoggerInterface $logger
    ) {
        parent::__construct('app:initialize:database');
        $this->documentManager = $documentManager;
        $this->logger = $logger;
    }

    protected function configure()
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->createAdminUser();
            $output->writeln('Created Admin user');
            $output->writeln('username: admin');
            $output->writeln('password: admin');
        } catch (MongoDBException $mongoDBException) {
            $output->writeln('Failed to crate Admin User');
        }
    }

    private function createAdminUser(): void
    {
        $user = new User(
            'admin',
            'admin@beanworks.com',
            Password::encrypt('admin')
        );
        $this->documentManager->persist($user);
        $this->documentManager->flush();
    }
}
