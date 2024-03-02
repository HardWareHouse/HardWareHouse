<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Entreprise;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserEntrepriseEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mail', null, [
                'label' => 'Adresse mail',
            ])
            ->add('username', null, [
                'label' => 'Nom',
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles de l\'utilisateur',
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Comptable' => 'ROLE_COMPTABLE',
                ],
                'expanded' => true,
                'multiple' => true,
            ])
            // ->add('isVerified',CheckboxType::class, [
            //     'label' => 'Compte vérifié',
            //     'required' => false,
            // ])
            // ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
