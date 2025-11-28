<?php

namespace App\Controller\Admin;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MessageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Message::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setDefaultSort([
                'createdAt' => 'DESC',
            ]);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();
        yield TextField::new('subject');
        yield TextField::new('name');
        yield TextField::new('email');
        yield BooleanField::new('read');
        yield TextareaField::new('message')
            ->hideOnIndex();
        yield DateTimeField::new('createdAt');
    }

    public function configureActions(Actions $actions): Actions
    {
        $switchReadAction = Action::new('switchRead')
            ->addCssClass('btn btn-success')
            ->setIcon('fa fa-envelope')
            ->linkToCrudAction('switchRead')
            ->setLabel(function (Message $message) {
                if ($message->isRead()) {
                    return 'Mark as Unread';
                } else {
                    return 'Mark as Read';
                }
            })
            ->displayIf(function (Message $message) {
                return true;
            });

        return parent::configureActions($actions)
            ->add(Crud::PAGE_DETAIL, $switchReadAction);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return parent::configureFilters($filters)
            ->add('read');
    }

    public function switchRead(AdminContext $adminContext, EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator): RedirectResponse
    {
        $request = $adminContext->getRequest();
        $id = $request->query->get('entityId');

        if (!$id) {
            throw new \RuntimeException('Impossible de récupérer l’ID du message.');
        }

        $message = $entityManager->getRepository(Message::class)->find($id);
        if (!$message) {
            throw new \RuntimeException('Message introuvable.');
        }

        $message->setRead(!$message->isRead());
        $entityManager->flush();

        $targetUrl = $adminUrlGenerator
            ->setController(self::class)
            ->setAction(Crud::PAGE_DETAIL)
            ->setEntityId($message->getId())
            ->generateUrl();

        return $this->redirect($targetUrl);
    }
}
