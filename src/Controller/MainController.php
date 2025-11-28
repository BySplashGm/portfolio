<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Experience;
use App\Entity\Message;
use App\Entity\Skill;
use App\Entity\SkillType;
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
        return $this->render('main/index.html.twig', ['title' => 'Accueil']);
    }

    #[Route('/about', name: 'about')]
    public function about(EntityManagerInterface $entityManager): Response
    {
        $experiences = $entityManager->getRepository(Experience::class)->findAll();
        $skills = $entityManager->getRepository(Skill::class)->findAll();
        $skillTypes = $entityManager->getRepository(SkillType::class)->findAll();
        return $this->render('main/about.html.twig', ['title' => 'À propos', 'skills' => $skills, 'skillTypes' => $skillTypes, 'experiences' => $experiences]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response {
        return $this->render('main/contact.html.twig', ['title' => 'Contact']);
    }

    #[Route('/contact/submit', name: 'contact_submit', methods: ['POST'])]
    public function submitContact(EntityManagerInterface $entityManager, Request $request) : Response
    {
        $message = new Message();
        $name = $request->get('name');
        $email = $request->get('email');
        $subject = $request->get('subject');
        $messageContent = $request->get('message');

        $message = new Message();
        $message->setName($name);
        $message->setEmail($email);
        $message->setSubject($subject);
        $message->setMessage($messageContent);

        $entityManager->persist($message);
        $entityManager->flush();

        $this->addFlash('success', 'Votre message a été envoyé avec succès.');
        sleep(1);
        return $this->redirectToRoute('index');
    }
}
