<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Skill;
use App\Entity\SkillType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SkillControllerTest extends WebTestCase
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

    public function testShowReturnsOkForExistingSkill(): void
    {
        $skillType = (new SkillType())->setLabel('HardSkill');
        $skill = (new Skill())
            ->setName('Symfony')
            ->setDescription('PHP framework')
            ->setType($skillType);

        $this->em->persist($skillType);
        $this->em->persist($skill);
        $this->em->flush();

        $this->client->request('GET', '/skill/'.$skill->getId());

        $this->assertResponseIsSuccessful();
    }

    public function testShowReturns404ForMissingSkill(): void
    {
        $this->client->request('GET', '/skill/9999');

        $this->assertResponseStatusCodeSame(404);
    }
}
