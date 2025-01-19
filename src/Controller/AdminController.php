<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Skill;
use App\Entity\SkillType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN', message: "Vous n'êtes pas autorisé à accéder à cette page.")]
class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $projects = $entityManager->getRepository(Project::class)->findAll();
        $skills = $entityManager->getRepository(Skill::class)->findAll();
        return $this->render('admin/index.html.twig', ['title' => "Panel administrateur", 'projects' => $projects, 'skills' => $skills]);
    }

    #[Route('/admin/newproject', name: 'newproject')]
    public function newproject(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory, Request $request): Response
    {

        $form = $formFactory->createBuilder()
            ->add('name', TextType::class, [
                'label' => 'Nom du projet',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez le nom du projet',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez une description',
                ],
            ])
            ->add('createdAt', DateType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'En cours' => 'en_cours',
                    'Terminé' => 'termine',
                    'Abandonné' => 'abandonne',
                ],
                'placeholder' => 'Sélectionnez un statut',
                'required' => true,
            ])
            ->add('skills', EntityType::class, [
                'label' => 'Compétences',
                'class' => Skill::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true, // Si tu veux des cases à cocher
            ])
            ->add('imagePath', TextType::class, [
                'label' => 'Image',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrez le chemin à partir de public',
                ],
            ])
            ->add('link', UrlType::class, [
                'label' => 'Lien du projet',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrez l’URL du projet',
                ],
            ])
            ->add('repolink', UrlType::class, [
                'label' => 'Lien du repo',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrez l’URL du projet',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer le projet',
            ])
        ->getForm();


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $project = new Project();
            $this->extracted($project, $data, $entityManager);

            foreach ($data['skills'] as $skill) {
                $project->addSkill($skill);
            }

            $this->redirectToRoute('admin');
        }

        $skills = $entityManager->getRepository(Skill::class)->findAll();
        return $this->render('admin/newproject.html.twig', ['title' => "Ajout d'un projet", 'skills' => $skills, 'form' => $form]);
    }

    #[Route('/admin/project/{id}', name: 'editproject', methods: ['GET', 'POST'])]
    public function editproject($id, EntityManagerInterface $entityManager, Request $request, FormFactoryInterface $formFactory): Response
    {
        $project = $entityManager->getRepository(Project::class)->find($id);
        $form = $formFactory->createBuilder()
            ->add('name', TextType::class, [
                'label' => 'Nom du projet',
                'required' => true,
                'data' => $project->getName(),
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'data' => $project->getDescription(),
            ])
            ->add('createdAt', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'required' => true,
                'data' => $project->getCreatedAt(),
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'data' => $project->getStatus(),
                'choices' => [
                    'En cours' => 'En cours',
                    'Terminé' => 'Terminé',
                    'Abandonné' => 'Abandonné',
                ]
            ])
            ->add('skills', EntityType::class, [
                'label' => 'Compétences',
                'class' => Skill::class,
                'data' => $project->getSkills(),
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('imagePath', TextType::class, [
                'label' => 'Image',
                'required' => false,
                'data' => $project->getImagePath(),
            ])
            ->add('link', UrlType::class, [
                'label' => 'Lien du projet',
                'required' => false,
                'data' => $project->getLink(),
            ])
            ->add('repolink', UrlType::class, [
                'label' => 'Lien du repo',
                'required' => false,
                'data' => $project->getRepolink(),
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->extracted($project, $data, $entityManager);
            return $this->redirectToRoute('admin');
        }
        $skills = $entityManager->getRepository(Skill::class)->findAll();
        return $this->render('admin/editproject.html.twig', ['title' => "Édition " . $project->getName(), 'project' => $project, 'skills' => $skills, 'form' => $form->createView()]);
    }

    #[Route('/admin/newskill', name: 'newskill', methods: ['GET', 'POST'])]
    public function newskill(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory, Request $request): Response
    {
        $form = $formFactory->createBuilder()
            ->add('name', TextType::class, [
                'label' => 'Nom de la compétence',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
            ])
            ->add('type', EntityType::class, [
                'class' => SkillType::class,
                'label' => 'Type',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $skill = new Skill();
            $data = $form->getData();

            $skill->setName($data['name']);
            $skill->setDescription($data['description']);
            $skill->setType($data['type']);

            $entityManager->persist($skill);
            $entityManager->flush();
            return $this->redirectToRoute('admin');
        }
        return $this->render('admin/newskill.html.twig', ['title' => "Ajout d'une compétence", 'form' => $form]);
    }
    #[Route('/admin/skill/{id}', name: 'editskill', methods: ['GET', 'POST'])]
    public function editskill($id, EntityManagerInterface $entityManager, Request $request, FormFactoryInterface $formFactory): Response
    {
        $skill = $entityManager->getRepository(Skill::class)->find($id);
        $form = $formFactory->createBuilder()
            ->add('name', TextType::class, [
                'label' => 'Nom du projet',
                'required' => true,
                'data' => $skill->getName(),
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'data' => $skill->getDescription(),
            ])
            ->add('type', EntityType::class, [
                'class' => SkillType::class,
                'label' => 'Type',
                'data' => $skill->getType(),
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $skill->setName($data['name']);
            $skill->setDescription($data['description']);
            $skill->setType($data['type']);

            $entityManager->persist($skill);
            $entityManager->flush();
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/editskill.html.twig', ['title' => "Édition " . $skill->getName(), 'skill' => $skill, 'form' => $form]);
    }

    /**
     * @param Project $project
     * @param mixed $data
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    public function extracted(Project $project, mixed $data, EntityManagerInterface $entityManager): void
    {
        $project->setName($data['name']);
        $project->setDescription($data['description']);
        $project->setCreatedAt($data['createdAt']);
        $project->setStatus($data['status']);

        $data['link'] != null ? $project->setLink($data['link']) : null;
        $data['imagePath'] != null ? $project->setImagePath($data['imagePath']) : null;
        $data['repolink'] != null ? $project->setLink($data['repolink']) : null;
        $entityManager->persist($project);
        $entityManager->flush();
    }
}
