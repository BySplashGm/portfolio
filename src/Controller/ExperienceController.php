<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Experience;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(EntityManagerInterface $entityManager, $id): Response
    {
        $experience = $entityManager->getRepository(Experience::class)->find($id);

        $converter = new CommonMarkConverter();
        $contentHTML = $converter->convert($experience->getDescription());

        return $this->render('experience/index.html.twig', ['experience' => $experience, 'content' => $contentHTML]);
    }
}
