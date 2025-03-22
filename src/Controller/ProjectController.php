<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Exception\CommonMarkException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    #[Route('/projects', name: 'projects')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $projects = $entityManager->getRepository(Project::class)->findAll();
        return $this->render('project/index.html.twig', ['title' => 'Projets', 'projects' => $projects]);
    }

    /**
     * @throws CommonMarkException
     */
    #[Route('/projects/{id}', name: 'project')]
    public function show(EntityManagerInterface $entityManager, $id): Response {
        $project = $entityManager->getRepository(Project::class)->find($id);


        $converter = new CommonMarkConverter();
        $contentHTML = $converter->convert($project->getDescription());

        return $this->render('project/show.html.twig', ['title' => $project->getName(), 'project' => $project, 'content' => $contentHTML]);
    }
}
