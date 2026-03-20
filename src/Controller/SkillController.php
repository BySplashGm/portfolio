<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\SkillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SkillController extends AbstractController
{
    #[Route('/skill/{id}', name: 'skill_show')]
    public function index(SkillRepository $skillRepository, int $id): Response
    {
        $skill = $skillRepository->findWithProjectsAndExperiences($id);

        if (!$skill) {
            throw $this->createNotFoundException('Skill not found');
        }

        return $this->render('skill/show.html.twig', ['skill' => $skill]);
    }
}
