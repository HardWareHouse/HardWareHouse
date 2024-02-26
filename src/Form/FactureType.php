<?php

namespace App\Form;

use App\Entity\Facture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('numero', TextType::class, [
            'attr' => ['placeholder' => 'FACT####']
            ])            
        ->add('dateFacturation', DateTimeType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime('now'),

            ])
            ->add('datePaiementDue', DateTimeType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime('now'),

            ])
            ->add('statutPaiement', ChoiceType::class, [
                'choices' => [
                    'paid' => 'Payé',
                    'unpaid' => 'Non-payé',
                    'late' => 'En retard',
                ],
            ])
            ->add('total', NumberType::class, [
                'attr' => ['placeholder' => '€']
            ])
            // ->add('CreatedAt', DateTimeType::class, [
            //     'widget' => 'single_text',
            //     'data' => new \DateTime('now'),
            
            // ])
            ->add('clientId')
            ->add('detailFactureId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
