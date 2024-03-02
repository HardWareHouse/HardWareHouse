<?php

namespace App\Form;

use App\Entity\Facture;
use App\Entity\Paiement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Security;

class PaiementType extends AbstractType
{   
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   
        $user = $this->security->getUser();
        $entreprise = $user->getEntreprise();

        $builder
            ->add('datePaiement')
            ->add('montant')
            ->add('methodePaiement', ChoiceType::class, [
                'choices' => [
                    'card' => 'Carte bancaire',
                    'check' => 'Chèque',
                    'cash' => 'Espèces',
                    'transfer' => 'Virement bancaire',
                ],
            ])
            ->add('factureId', EntityType::class, [
                'class' => Facture::class,
                'query_builder' => function (EntityRepository $er) use ($entreprise) {
                    return $er->createQueryBuilder('f')
                        ->andWhere('f.entrepriseId = :entreprise')
                        ->setParameter('entreprise', $entreprise);
                },
                'choice_label' => 'numero',
                'placeholder' => 'Choisissez une Facture',
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
