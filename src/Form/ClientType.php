<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ClientType extends AbstractType
{   
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'attr' => ['placeholder' => 'Nom du client']
        ])
            ->add('prenom', TextType::class, [
                'attr' => ['placeholder' => 'Prénom du client']
            ])
            ->add('adresse', TextType::class, [
                'attr' => ['placeholder' => 'Adresse du client']
            ])
            ->add('codePostal', IntegerType::class, [
                'attr' => ['placeholder' => 'Code postal']
            ])
            ->add('ville', TextType::class, [
                'attr' => ['placeholder' => 'Ville']
            ])
            ->add('email', TextType::class, [
                'attr' => ['placeholder' => 'Email du client']
            ])
            ->add('telephone', TextType::class, [
                'attr' => ['placeholder' => 'Téléphone du client']
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
            'data_class' => Client::class,
        ]);
    }
}
