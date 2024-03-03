<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\DetailDevis;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class DetailDevisType extends AbstractType
{   
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $user = $this->security->getUser();
        $entreprise = $user->getEntreprise();

        $builder
            ->add('produit', EntityType::class, [
                'class' => Produit::class,
                'query_builder' => function (EntityRepository $er) use ($entreprise) {
                    return $er->createQueryBuilder('p')
                        ->andWhere('p.entrepriseId = :entreprise')
                        ->setParameter('entreprise', $entreprise);
                },
                'choice_label' => 'nom',
                'placeholder' => 'Choisissez un produit',
            ])
            ->add('quantite', IntegerType::class, [
                'label' => 'Quantité',
                'attr' => [
                    'min' => 1,
                    'max' => 50,
                ],
                'constraints' => [
                    new Callback([
                        'callback' => [$this, 'validateQuantite'],
                    ]),
                ],
            ])
        ;
    }
    public function validateQuantite($value, ExecutionContextInterface $context)
    {
        $form = $context->getObject();

        $parentForm = $form->getParent();
        $data = $parentForm->getData();

        $quantite = $data->getQuantite();
        $produit = $data->getProduit(); // Obtient le produit sélectionné
        $stockRestant = $produit->getStock();

        if ($value > $stockRestant) {
            $context->buildViolation('La quantité sélectionnée dépasse le stock restant pour ce produit. Stock restant : '.$stockRestant.'.')
                ->addViolation();
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DetailDevis::class,
        ]);
    }
}
