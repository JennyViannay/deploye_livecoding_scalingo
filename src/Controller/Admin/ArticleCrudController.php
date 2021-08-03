<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Field\PictureFormField;
use App\Form\PictureFormType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [];
        if ($pageName === Crud::PAGE_INDEX) {
            array_push(
                $fields,
                IdField::new('id', 'Référence'),
                TextField::new('title', 'Titre'),
                DateField::new('createdAt', 'Créé le'),
                ImageField::new('image', 'Image')->setBasePath('/uploads/images/articles'),
            );
        }
        if ($pageName === Crud::PAGE_DETAIL) {
            array_push(
                $fields,
                IdField::new('id', 'Référence'),
                TextField::new('title', 'Titre'),
                DateField::new('createdAt', 'Créé le'),
                ImageField::new('image', 'Image')->setBasePath('/uploads/images/articles'),
                AssociationField::new('mainIllustration', 'Main Illustration')->setTemplatePath('admin/details_picture.html.twig'),
                CollectionField::new('pictures')->setTemplatePath('admin/details_pictures.html.twig'),
            );
        }
        if ($pageName === Crud::PAGE_EDIT || $pageName === Crud::PAGE_NEW) {
            array_push(
                $fields,
                TextField::new('title', 'Titre'),
                TextEditorField::new('content', 'Contenu'),
                AssociationField::new('category', 'Category'),
                TextField::new('imageFile', 'File')->setFormType(VichImageType::class),
                CollectionField::new('pictures')->setCustomOptions([
                    'allowAdd' => true,
                    'allowDelete' => true,
                    'entryType'=> PictureFormType::class 
                ]),
                PictureFormField::new('mainIllustration')
            );
        }
        
        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Add Project');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fas fa-pencil-alt')->setLabel(false);
            })
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action->setIcon('fas fa-eye')->setLabel(false);
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fas fa-trash')->setLabel(false);
            })
        ;
    }
}
