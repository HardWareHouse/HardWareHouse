<?php

namespace App\Form;

use App\Entity\Devis;
use App\Entity\DetailDevis;
use App\Entity\Client;
use App\Entity\Entreprise;
use App\Form\DetailDevisType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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

        $devis = $builder->getData();
        $numeroDevisExiste = $devis && $devis->getNumero();
        
        if (!$numeroDevisExiste) {
            $dernierDevis = $entreprise->getDevisId()->last();
            if ($dernierDevis) {
                $numeroDernierDevis = $dernierDevis->getNumero();
                $numeroParties = explode('#', $numeroDernierDevis);
                $count = intval($numeroParties[1]) + 1;
            } else {
                $count = 1;
            }
            $numero = sprintf("DEVIS#%03d", $count);
        } else {
            $numero = $devis->getNumero();
        }


        $builder
            ->add('numero', TextType::class, [
                'attr' => [
                    'placeholder' => 'estimate_placeholder',
                    'readonly' => 'readonly',
                    'value' => $numero,
                ],
                'mapped' => false,
            ])       
            ->add('dateCreation', DateType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime('now'),

            ])
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
            ->add('detailDevis', CollectionType::class, [
                'required' => true,
                'entry_type' => DetailDevisType::class,
                'label' => false,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false, 
             ]);
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
