<?php

namespace App\Form;

use App\Entity\Devis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', TextType::class, [
            'attr' => ['placeholder' => 'DEVIS####']
            ])             
            ->add('dateCreation', DateType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime('now'),

            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Approuvé' => 'Approuvé',
                    'En attente' => 'En attente',
                    'Refusé' => 'Refusé',
                ],
            ])
            ->add('total')
            ->add('CreatedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime('now'),
            
            ])
            ->add('clientId')
            ->add('detailDevisId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
