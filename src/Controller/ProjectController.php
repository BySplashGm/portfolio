<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProjectRepository;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Exception\CommonMarkException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    #[Route('/projects', name: 'projects')]
    public function index(ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->findAll();

        return $this->render('project/index.html.twig', ['projects' => $projects]);
    }

    /**
     * @throws CommonMarkException
     */
    #[Route('/projects/{id}', name: 'project')]
    public function show(ProjectRepository $projectRepository, CommonMarkConverter $converter, int $id): Response
    {
        $project = $projectRepository->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Project not found');
        }

        $contentHTML = $converter->convert($project->getDescription());

        return $this->render('project/show.html.twig', ['project' => $project, 'content' => $contentHTML]);
    }
}
