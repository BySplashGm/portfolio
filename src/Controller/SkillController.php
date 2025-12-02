<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Skill;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SkillController extends AbstractController
{
    #[Route('/skill/{id}', name: 'skill_show')]
    public function index(EntityManagerInterface $entityManager, $id): Response
    {
        $skill = $entityManager->getRepository(Skill::class)->find($id);

        return $this->render('skill/show.html.twig', ['skill' => $skill]);
    }
}
