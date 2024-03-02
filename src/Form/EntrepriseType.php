<?php

namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use App\Form\MediaType as MediaFormType;
use Symfony\Component\Form\Extension\Core\Type\TextType; 

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['placeholder' => 'Nom de l\'entreprise']
            ])
            ->add('adresse', TextType::class, [
                'attr' => ['placeholder' => 'Adresse de l\'entreprise']
            ])
            ->add('codePostal', TextType::class, [
                'attr' => ['placeholder' => 'Code postal']
            ])
            ->add('ville', TextType::class, [
                'attr' => ['placeholder' => 'Ville']
            ])
            ->add('description', TextType::class, [
                'attr' => ['placeholder' => 'Description de l\'entreprise']
            ])
            ->add('siren', TextType::class, [
                'attr' => ['placeholder' => 'SIREN / SIRET']
            ])
            ->add('informationFiscale', TextType::class, [
                'attr' => ['placeholder' => 'Information fiscale']
            ])
            ->add('email', TextType::class, [
                'attr' => ['placeholder' => 'Email de l\'entreprise']
            ])
            ->add('telephone', TextType::class, [
                'attr' => ['placeholder' => 'Téléphone de l\'entreprise']
            ])
            ->add('siteWeb', TextType::class, [
                'attr' => ['placeholder' => 'Site web de l\'entreprise']
            ])
            ->add('logo', MediaFormType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
}
