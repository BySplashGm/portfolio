<?php

namespace App\Controller\Admin;

use App\Entity\Experience;
use App\Entity\Message;
use App\Entity\Project;
use App\Entity\Skill;
use App\Entity\SkillType;
use App\Entity\User;
use App\Repository\MessageRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    private MessageRepository $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    public function index(): Response
    {
        $messages = $this->messageRepository->findLatest();

        return $this->render('admin/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Portfolio Dashboard')
            ->setFaviconPath('favicon.png');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Administration');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Messages', 'fas fa-message', Message::class);
        yield MenuItem::linkToCrud('Users', 'fa fa-users', User::class);

        yield MenuItem::section('Content');
        yield MenuItem::linkToCrud('Experiences', 'fas fa-briefcase', Experience::class);
        yield MenuItem::linkToCrud('Projects', 'fa fa-diagram-project', Project::class);
        yield MenuItem::linkToCrud('Skills', 'fa fa-folder', Skill::class);
        yield MenuItem::linkToCrud('SkillTypes', 'fa fa-folder-plus', SkillType::class);

        yield MenuItem::section('Public pages');
        yield MenuItem::linkToUrl('Homepage', 'fas fa-home', $this->generateUrl('index'));
        yield MenuItem::linkToUrl('About', 'fas fa-home', $this->generateUrl('about'));
        yield MenuItem::linkToUrl('Projects', 'fas fa-home', $this->generateUrl('projects'));
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_DETAIL, Action::EDIT, function (Action $action) {
                return $action->setIcon('fas fa-edit');
            })
            ->update(Crud::PAGE_DETAIL, Action::INDEX, function (Action $action) {
                return $action->setIcon('fas fa-list');
            });
    }
}
