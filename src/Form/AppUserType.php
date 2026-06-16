<?php

namespace App\Form;

use App\Entity\AppUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AppUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isNew = $options['is_new'];

        $builder
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('nom', TextType::class, ['label' => 'Nom'])
            ->add('prenom', TextType::class, ['label' => 'Prénom'])
            ->add('roles', ChoiceType::class, [
                'label'    => 'Rôle(s)',
                'choices'  => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Enseignant'     => 'ROLE_ENSEIGNANT',
                ],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped'      => false,
                'required'    => $isNew,
                'label'       => $isNew
                    ? 'Mot de passe'
                    : 'Nouveau mot de passe (laisser vide pour ne pas changer)',
                'constraints' => $isNew
                    ? [
                        new NotBlank(['message' => 'Le mot de passe est obligatoire.']),
                        new Length(['min' => 6, 'minMessage' => 'Minimum {{ limit }} caractères.']),
                    ]
                    : [new Length(['min' => 6, 'minMessage' => 'Minimum {{ limit }} caractères.'])],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AppUser::class,
            'is_new'     => true,
        ]);
    }
}
