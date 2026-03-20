<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ExperienceRepository;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Exception\CommonMarkException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExperienceController extends AbstractController
{
    /**
     * @throws CommonMarkException
     */
    #[Route('/experience/{id}', name: 'experience_show')]
    public function show(ExperienceRepository $experienceRepository, CommonMarkConverter $converter, int $id): Response
    {
        $experience = $experienceRepository->find($id);

        if (!$experience) {
            throw $this->createNotFoundException('Experience not found');
        }

        $contentHTML = $converter->convert($experience->getDescription());

        return $this->render('experience/index.html.twig', ['experience' => $experience, 'content' => $contentHTML]);
    }
}
