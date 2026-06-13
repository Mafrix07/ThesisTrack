<?php

namespace App\Form;

use App\Entity\Salle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'Code de la salle',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Ex: Salle A1']
            ])
            ->add('capacite', IntegerType::class, [
                'label' => 'Capacité (places)',
                'attr' => ['class' => 'form-control', 'min' => 1]
            ])
            ->add('localisation', TextType::class, [
                'label' => 'Localisation',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Ex: Bâtiment B, 2ème étage']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Salle::class,
        ]);
    }
}
