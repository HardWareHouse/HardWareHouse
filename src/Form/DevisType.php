<?php

namespace App\Form;

use App\Entity\Devis;
use App\Entity\Client;
use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class DevisType extends AbstractType
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
            ->add('numero', TextType::class, [
            'attr' => ['placeholder' => 'estimate_placeholder']
            ])             
            ->add('dateCreation', DateType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime('now'),

            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'En attente',
                    'Approuvé' => 'Approuvé',
                    'Refusé' => 'Refusé',
                ],
            ])
            ->add('total')
            // ->add('CreatedAt', DateTimeType::class, [
            //     'widget' => 'single_text',
            //     'data' => new \DateTime('now'),
            // ])
            ->add('clientId', EntityType::class, [
                'class' => Client::class, // Entité Client
                'query_builder' => function (EntityRepository $er) use ($entreprise) {
                    return $er->createQueryBuilder('c')
                        ->andWhere('c.entrepriseId = :entreprise')
                        ->setParameter('entreprise', $entreprise);
                },
                'choice_label' => 'nom',
                'placeholder' => 'Choisissez un client',
            ])
            ->add('detailDevisId')
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
            'data_class' => Devis::class,
        ]);
    }
}
