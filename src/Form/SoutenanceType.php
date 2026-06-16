<?php

namespace App\Form;

use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Entity\Salle;
use App\Entity\Soutenance;
use App\Repository\EtudiantRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SoutenanceType extends AbstractType
{
    public function __construct(private EtudiantRepository $etudiantRepo) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $etudiants = $this->etudiantRepo->findWithoutSoutenance($options['current_etudiant_id']);

        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label'  => 'Date de soutenance',
                'attr'   => ['class' => 'form-control'],
            ])
            ->add('heure', TimeType::class, [
                'widget' => 'single_text',
                'label'  => 'Heure',
                'attr'   => ['class' => 'form-control'],
            ])
            ->add('salle', EntityType::class, [
                'class'        => Salle::class,
                'choice_label' => fn(Salle $s) => $s->getCode().' — '.$s->getLocalisation().' (cap. '.$s->getCapacite().')',
                'placeholder'  => '— Sélectionner une salle —',
                'label'        => 'Salle',
                'attr'         => ['class' => 'form-select'],
            ])
            ->add('etudiant', EntityType::class, [
                'class'        => Etudiant::class,
                'choices'      => $etudiants,
                'choice_label' => fn(Etudiant $e) => $e->getPrenom().' '.$e->getNom().' ('.$e->getFiliere().')',
                'placeholder'  => '— Sélectionner un étudiant —',
                'label'        => 'Étudiant',
                'attr'         => ['class' => 'form-select'],
            ])
            ->add('president', EntityType::class, [
                'class'        => Enseignant::class,
                'choice_label' => fn(Enseignant $e) => $e->getPrenom().' '.$e->getNom().' ('.$e->getSpecialite().')',
                'placeholder'  => '— Choisir le président —',
                'label'        => 'Président du Jury',
                'attr'         => ['class' => 'form-select'],
            ])
            ->add('rapporteur', EntityType::class, [
                'class'        => Enseignant::class,
                'choice_label' => fn(Enseignant $e) => $e->getPrenom().' '.$e->getNom().' ('.$e->getSpecialite().')',
                'placeholder'  => '— Choisir le rapporteur —',
                'label'        => 'Rapporteur',
                'attr'         => ['class' => 'form-select'],
            ])
            ->add('examinateur', EntityType::class, [
                'class'        => Enseignant::class,
                'choice_label' => fn(Enseignant $e) => $e->getPrenom().' '.$e->getNom().' ('.$e->getSpecialite().')',
                'placeholder'  => "— Choisir l'examinateur —",
                'label'        => 'Examinateur',
                'attr'         => ['class' => 'form-select'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'          => Soutenance::class,
            'current_etudiant_id' => null,
        ]);
        $resolver->setAllowedTypes('current_etudiant_id', ['null', 'int']);
    }
}
