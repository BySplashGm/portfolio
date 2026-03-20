<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ExperienceRepository;
use App\Repository\ProjectRepository;
use App\Repository\SkillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SitemapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'sitemap')]
    public function index(
        Request $request,
        ProjectRepository $projectRepository,
        ExperienceRepository $experienceRepository,
        SkillRepository $skillRepository,
    ): Response {
        $base = $request->getSchemeAndHttpHost();

        $urls = [
            ['loc' => $base.'/', 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => $base.'/about', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $base.'/projects', 'changefreq' => 'weekly', 'priority' => '0.9'],
        ];

        foreach ($projectRepository->findAll() as $project) {
            $urls[] = ['loc' => $base.'/projects/'.$project->getId(), 'changefreq' => 'monthly', 'priority' => '0.7'];
        }

        foreach ($experienceRepository->findAll() as $experience) {
            $urls[] = ['loc' => $base.'/experience/'.$experience->getId(), 'changefreq' => 'monthly', 'priority' => '0.6'];
        }

        foreach ($skillRepository->findAll() as $skill) {
            $urls[] = ['loc' => $base.'/skill/'.$skill->getId(), 'changefreq' => 'monthly', 'priority' => '0.5'];
        }

        return new Response(
            $this->renderView('sitemap.xml.twig', ['urls' => $urls]),
            200,
            ['Content-Type' => 'application/xml']
        );
    }
}
