<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Experience;
use App\Entity\Message;
use App\Entity\Skill;
use App\Entity\SkillType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
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
        $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

    public function testAboutReturnsOk(): void
    {
        $skillType = (new SkillType())->setLabel('HardSkill');
        $skill = (new Skill())->setName('PHP')->setDescription('PHP lang')->setType($skillType);
        $experience = (new Experience())
            ->setLabel('Dev job')
            ->setCompany('Acme')
            ->setDescription('Some work')
            ->setShortDescription('Short');

        $this->em->persist($skillType);
        $this->em->persist($skill);
        $this->em->persist($experience);
        $this->em->flush();

        $this->client->request('GET', '/about');

        $this->assertResponseIsSuccessful();
    }

    public function testContactPageReturnsOk(): void
    {
        $this->client->request('GET', '/contact');

        $this->assertResponseIsSuccessful();
    }

    public function testContactFormSubmitValidData(): void
    {
        $this->client->request('POST', '/contact', [
            'contact_form' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'subject' => 'Hello',
                'message' => 'This is a test message body.',
            ],
        ]);

        $this->assertResponseRedirects('/');

        $message = $this->em->getRepository(Message::class)->findOneBy(['email' => 'john@example.com']);
        $this->assertNotNull($message);
        $this->assertSame('John Doe', $message->getName());
        $this->assertSame('Hello', $message->getSubject());
        $this->assertFalse($message->isRead());
    }

    public function testContactFormSubmitInvalidEmail(): void
    {
        $this->client->request('POST', '/contact', [
            'contact_form' => [
                'name' => 'John',
                'email' => 'not-an-email',
                'subject' => 'Hi',
                'message' => 'Some message here.',
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSame(0, $this->em->getRepository(Message::class)->count([]));
    }

    public function testContactFormSubmitBlankFields(): void
    {
        $this->client->request('POST', '/contact', [
            'contact_form' => [
                'name' => '',
                'email' => '',
                'subject' => '',
                'message' => '',
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSame(0, $this->em->getRepository(Message::class)->count([]));
    }
}
