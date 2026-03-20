<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($this->em->getMetadataFactory()->getAllMetadata());
    }

    public function testIndexReturnsOk(): void
    {
        $this->client->request('GET', '/projects');

        $this->assertResponseIsSuccessful();
    }

    public function testShowReturnsOkForExistingProject(): void
    {
        $project = (new Project())
            ->setName('My Project')
            ->setDescription('## Description')
            ->setShortDescription('Short')
            ->setStatus('Terminé');

        $this->em->persist($project);
        $this->em->flush();

        $this->client->request('GET', '/projects/'.$project->getId());

        $this->assertResponseIsSuccessful();
    }

    public function testShowReturns404ForMissingProject(): void
    {
        $this->client->request('GET', '/projects/9999');

        $this->assertResponseStatusCodeSame(404);
    }
}
