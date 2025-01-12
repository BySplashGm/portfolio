<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Experience;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExperienceController extends AbstractController
{
    #[Route('/experience/{id}', name: 'experience_show')]
    public function index(EntityManagerInterface $entityManager, $id): Response
    {
        $experience = $entityManager->getRepository(Experience::class)->find($id);
        return $this->render('experience/index.html.twig', ['title' => $experience->getLabel() , 'experience' => $experience]);
    }
}
