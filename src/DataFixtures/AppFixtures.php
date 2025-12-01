<?php

namespace App\DataFixtures;

use App\Entity\Message;
use App\Entity\Project;
use App\Entity\Skill;
use App\Entity\SkillType;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $user = new User();
        $user->setPassword(password_hash('adminpass', PASSWORD_BCRYPT));
        $user->setEmail('admin@test.com');
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $itemCount = 10;
        for ($i = 1; $i <= $itemCount; ++$i) {
            $message = new Message();
            $message->setEmail($faker->email);
            $message->setName($faker->name);
            $message->setSubject($faker->sentence());
            $message->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 years')));
            $message->setMessage($faker->paragraphs(3, true));
            $message->setRead($faker->boolean(70));
            $manager->persist($message);
        }

        $softSkill = new SkillType();
        $softSkill->setLabel('SoftSkill');
        $hardSkill = new SkillType();
        $hardSkill->setLabel('HardSkill');
        $manager->persist($softSkill);
        $manager->persist($hardSkill);

        $skillTypes = [$hardSkill, $softSkill];
        $skills = [];

        for ($i = 1; $i <= $itemCount * 2; ++$i) {
            $skill = new Skill();
            $skill->setName($faker->word);
            $skill->setDescription($faker->sentence());
            $skill->setType($skillTypes[array_rand($skillTypes)]);
            $manager->persist($skill);
            $skills[] = $skill;
        }

        $status = ['Terminé', 'En cours', 'Abandonné'];

        for ($i = 1; $i <= $itemCount / 2; ++$i) {
            $project = new Project();
            $project->setName($faker->word);
            $project->setDescription($faker->paragraphs(4, true));
            $project->setShortDescription($faker->sentence());
            $project->setRepolink($faker->url());
            $project->setStatus($status[array_rand($status)]);
            $project->setLink($faker->url());
            $manager->persist($project);
            for ($j = 1; $j <= random_int(3, 7); ++$j) {
                $project->addSkill($skills[array_rand($skills)]);
            }
        }

        $manager->flush();
    }
}
