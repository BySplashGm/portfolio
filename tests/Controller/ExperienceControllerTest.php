<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Experience;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExperienceControllerTest extends WebTestCase
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

    public function testShowReturnsOkForExistingExperience(): void
    {
        $experience = (new Experience())
            ->setLabel('Dev Intern')
            ->setCompany('Acme Corp')
            ->setDescription('## Work done')
            ->setShortDescription('Short summary');

        $this->em->persist($experience);
        $this->em->flush();

        $this->client->request('GET', '/experience/'.$experience->getId());

        $this->assertResponseIsSuccessful();
    }

    public function testShowReturns404ForMissingExperience(): void
    {
        $this->client->request('GET', '/experience/9999');

        $this->assertResponseStatusCodeSame(404);
    }
}
