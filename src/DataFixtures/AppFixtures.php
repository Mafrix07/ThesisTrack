<?php

namespace App\DataFixtures;

use App\Entity\AppUser;
use App\Entity\Enseignant;
use App\Entity\Etudiant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création de l'Admin
        $admin = new AppUser();
        $admin->setEmail('admin@soutenance.pro');
        $admin->setNom('ADMIN');
        $admin->setPrenom('System');
        $admin->setRoles(['ROLE_ADMIN']);
        $password = $this->hasher->hashPassword($admin, 'admin123');
        $admin->setPassword($password);
        $manager->persist($admin);

        // Création d'un Enseignant
        $teacherUser = new AppUser();
        $teacherUser->setEmail('prof@soutenance.pro');
        $teacherUser->setNom('DOE');
        $teacherUser->setPrenom('John');
        $teacherUser->setRoles(['ROLE_ENSEIGNANT']);
        $password = $this->hasher->hashPassword($teacherUser, 'prof123');
        $teacherUser->setPassword($password);
        $manager->persist($teacherUser);

        $enseignant = new Enseignant();
        $enseignant->setNom('DOE');
        $enseignant->setPrenom('John');
        $enseignant->setEmail('prof@soutenance.pro');
        $enseignant->setSpecialite('Génie Logiciel');
        $enseignant->setUser($teacherUser);
        $manager->persist($enseignant);

        // Création d'étudiants
        $filiere = 'Informatique';
        for ($i = 1; $i <= 5; $i++) {
            $etudiant = new Etudiant();
            $etudiant->setNom('Etudiant' . $i);
            $etudiant->setPrenom('Prenom' . $i);
            $etudiant->setEmail('etudiant' . $i . '@univ.test');
            $etudiant->setFiliere($filiere);
            $etudiant->setThemeMemoire('Thème de recherche n°' . $i);
            $manager->persist($etudiant);
        }

        $manager->flush();
    }
}
