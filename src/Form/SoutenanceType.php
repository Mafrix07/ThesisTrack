<?php

namespace App\Form;

use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Entity\Salle;
use App\Entity\Soutenance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SoutenanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('etudiant', EntityType::class, [
                'class' => Etudiant::class,
                'choice_label' => function (Etudiant $etudiant) {
                    return $etudiant->getPrenom() . ' ' . $etudiant->getNom() . ' (' . $etudiant->getFiliere() . ')';
                },
                'label' => 'Étudiant',
                'attr' => ['class' => 'form-select']
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de soutenance',
                'attr' => ['class' => 'form-control']
            ])
            ->add('heure', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Heure',
                'attr' => ['class' => 'form-control']
            ])
            ->add('salle', EntityType::class, [
                'class' => Salle::class,
                'choice_label' => 'code',
                'label' => 'Salle',
                'attr' => ['class' => 'form-select']
            ])
            ->add('president', EntityType::class, [
                'class' => Enseignant::class,
                'choice_label' => function (Enseignant $ens) {
                    return $ens->getPrenom() . ' ' . $ens->getNom();
                },
                'label' => 'Président du Jury',
                'attr' => ['class' => 'form-select']
            ])
            ->add('rapporteur', EntityType::class, [
                'class' => Enseignant::class,
                'choice_label' => function (Enseignant $ens) {
                    return $ens->getPrenom() . ' ' . $ens->getNom();
                },
                'label' => 'Rapporteur',
                'attr' => ['class' => 'form-select']
            ])
            ->add('examinateur', EntityType::class, [
                'class' => Enseignant::class,
                'choice_label' => function (Enseignant $ens) {
                    return $ens->getPrenom() . ' ' . $ens->getNom();
                },
                'label' => 'Examinateur',
                'attr' => ['class' => 'form-select']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Soutenance::class,
        ]);
    }
}
