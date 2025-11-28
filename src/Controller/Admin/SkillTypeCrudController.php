<?php

namespace App\Controller\Admin;

use App\Entity\SkillType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SkillTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SkillType::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('label');
        yield AssociationField::new('skills');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, 'Skill Types');
    }
}
