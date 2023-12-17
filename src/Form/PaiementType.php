<?php

namespace App\Form;

use App\Entity\Facture;
use App\Entity\Paiement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PaiementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('datePaiement')
            ->add('montant')
            ->add('methodePaiement', ChoiceType::class, [
                'choices' => [
                    'Carte bancaire' => 'Carte bancaire',
                    'Chèque' => 'Chèque',
                    'Espèces' => 'Espèces',
                    'Virement bancaire' => 'Virement bancaire',
                ],
            ])
            ->add('factureId', EntityType::class, [
                'class' => Facture::class,
                'choice_label' => 'numero',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paiement::class,
        ]);
    }
}
