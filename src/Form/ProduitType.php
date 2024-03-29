<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Entreprise;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use App\Entity\Categorie;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProduitType extends AbstractType
{   
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['placeholder' => 'Nom du produit']
            ])
            ->add('description', TextType::class, [
                'attr' => ['placeholder' => 'Description du produit']
            ])
            ->add('prix', IntegerType::class, [
                'attr' => ['placeholder' => 'Prix du produit']
            ])
            ->add('stock', IntegerType::class, [
                'attr' => ['placeholder' => 'Nombre de produits en stock']
            ])
            ->add('categorieId', EntityType::class, [
                'class' => Categorie::class,
                'label' => 'Catégorie',
                'choice_label' => 'nom', 
                'placeholder' => 'Sélectionnez la catégorie auquel le produit appartient',
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
            'data_class' => Produit::class,
        ]);
    }
}
