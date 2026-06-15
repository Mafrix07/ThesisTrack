<?php

namespace App\DataFixtures;

use App\Entity\AppUser;
use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Entity\Salle;
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

        // Création de 3 Enseignants pour les jurys
        $specialites = ['Génie Logiciel', 'Réseaux', 'Sécurité'];
        for ($i = 1; $i <= 3; $i++) {
            $teacherUser = new AppUser();
            $email = "prof{$i}@soutenance.pro";
            $teacherUser->setEmail($email);
            $teacherUser->setNom('NOM_PROF' . $i);
            $teacherUser->setPrenom('PRENOM_PROF' . $i);
            $teacherUser->setRoles(['ROLE_ENSEIGNANT']);
            $password = $this->hasher->hashPassword($teacherUser, 'prof123');
            $teacherUser->setPassword($password);
            $manager->persist($teacherUser);

            $enseignant = new Enseignant();
            $enseignant->setNom('NOM_PROF' . $i);
            $enseignant->setPrenom('PRENOM_PROF' . $i);
            $enseignant->setEmail($email);
            $enseignant->setSpecialite($specialites[$i-1]);
            $enseignant->setUser($teacherUser);
            $manager->persist($enseignant);
        }

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

        // Création de salles
        $salles = [
            ['A101', 30, 'Bâtiment A, 1er étage'],
            ['A102', 20, 'Bâtiment A, 1er étage'],
            ['B201', 50, 'Bâtiment B, 2ème étage'],
        ];

        foreach ($salles as $data) {
            $salle = new Salle();
            $salle->setCode($data[0]);
            $salle->setCapacite($data[1]);
            $salle->setLocalisation($data[2]);
            $manager->persist($salle);
        }

        $manager->flush();
    }
}
