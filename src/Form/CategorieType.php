<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Entreprise;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategorieType extends AbstractType
{   
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['placeholder' => 'Nom de la catégorie']
            ])
            ->add('description', TextType::class, [
                'attr' => ['placeholder' => 'Description de la catégorie']
            ])
        ;

        $isAdmin = $this->security->isGranted('ROLE_ADMIN');
        if ($isAdmin) {
            $builder->add('entrepriseId', EntityType::class, [
                'class' => Entreprise::class,
                'label' => 'Entreprise',
                'choice_label' => 'nom', 
                'placeholder' => 'Sélectionnez l\'entreprise auquel la catégorie appartient',
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
