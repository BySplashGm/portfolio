<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Experience;
use App\Entity\Message;
use App\Form\ContactFormType;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    #[Route('/about', name: 'about')]
    public function about(EntityManagerInterface $entityManager, SkillRepository $skillRepository): Response
    {
        $experiences = $entityManager->getRepository(Experience::class)->findAll();

        $skillsByType = [];
        foreach ($skillRepository->findAllWithType() as $skill) {
            $skillsByType[$skill->getType()->getLabel()][] = $skill;
        }

        return $this->render('main/about.html.twig', ['skillsByType' => $skillsByType, 'experiences' => $experiences]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message();
        $form = $this->createForm(ContactFormType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', 'Votre message a été envoyé avec succès.');

            return $this->redirectToRoute('index');
        }

        return $this->render('main/contact.html.twig', ['form' => $form]);
    }
}
