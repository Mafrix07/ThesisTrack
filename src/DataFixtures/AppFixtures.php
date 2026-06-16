<?php

namespace App\DataFixtures;

use App\Entity\AppUser;
use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Entity\Salle;
use App\Entity\Soutenance;
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
        // Admin
        $admin = new AppUser();
        $admin->setEmail('admin@soutenance.pro');
        $admin->setNom('ADMIN');
        $admin->setPrenom('System');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // Enseignants
        $enseignantsData = [
            ['MBAYE',     'Ibrahima',  'prof1@soutenance.pro', 'Génie Logiciel'],
            ['DIALLO',    'Fatoumata', 'prof2@soutenance.pro', 'Réseaux & Télécoms'],
            ['OUEDRAOGO', 'Seydou',    'prof3@soutenance.pro', 'Cybersécurité'],
        ];

        $ens = [];
        foreach ($enseignantsData as [$nom, $prenom, $email, $specialite]) {
            $user = new AppUser();
            $user->setEmail($email);
            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setRoles(['ROLE_ENSEIGNANT']);
            $user->setPassword($this->hasher->hashPassword($user, 'prof123'));
            $manager->persist($user);

            $enseignant = new Enseignant();
            $enseignant->setNom($nom);
            $enseignant->setPrenom($prenom);
            $enseignant->setEmail($email);
            $enseignant->setSpecialite($specialite);
            $enseignant->setUser($user);
            $manager->persist($enseignant);

            $ens[] = $enseignant;
        }

        // Étudiants
        $etudiantsData = [
            ['KONATÉ',    'Aminata', 'a.konate@univ.test',    'Licence GL',  'Détection d\'intrusions par apprentissage automatique'],
            ['TRAORÉ',    'Moussa',  'm.traore@univ.test',    'Licence WIM', 'Développement d\'une plateforme e-learning adaptive'],
            ['SAWADOGO',  'Mariam',  'm.sawadogo@univ.test',  'Licence GL',  'Optimisation des algorithmes de tri parallèle'],
            ['BALDE',     'Thierno', 't.balde@univ.test',     'Licence WIM', 'Application mobile de gestion de stock en temps réel'],
            ['COULIBALY', 'Adama',   'a.coulibaly@univ.test', 'Licence GL',  'Architecture microservices pour systèmes bancaires'],
        ];

        $etu = [];
        foreach ($etudiantsData as [$nom, $prenom, $email, $filiere, $theme]) {
            $etudiant = new Etudiant();
            $etudiant->setNom($nom);
            $etudiant->setPrenom($prenom);
            $etudiant->setEmail($email);
            $etudiant->setFiliere($filiere);
            $etudiant->setThemeMemoire($theme);
            $manager->persist($etudiant);

            $etu[] = $etudiant;
        }

        // Salles
        $sallesData = [
            ['A101', 30, 'Bâtiment A, 1er étage'],
            ['A102', 20, 'Bâtiment A, 1er étage'],
            ['B201', 50, 'Bâtiment B, 2ème étage'],
        ];

        $sal = [];
        foreach ($sallesData as [$code, $capacite, $loc]) {
            $salle = new Salle();
            $salle->setCode($code);
            $salle->setCapacite($capacite);
            $salle->setLocalisation($loc);
            $manager->persist($salle);

            $sal[] = $salle;
        }

        // Soutenances — [etudiant, date, heure, salle, president, rapporteur, examinateur]
        // Ens: 0=MBAYE, 1=DIALLO, 2=OUEDRAOGO  |  Sal: 0=A101, 1=A102, 2=B201
        $soutenancesData = [
            [$etu[0], '+9 days',  '09:00', $sal[0], $ens[0], $ens[1], $ens[2]],
            [$etu[1], '+9 days',  '11:00', $sal[1], $ens[1], $ens[2], $ens[0]],
            [$etu[2], '+10 days', '09:00', $sal[0], $ens[2], $ens[0], $ens[1]],
            [$etu[3], '+10 days', '11:00', $sal[2], $ens[0], $ens[2], $ens[1]],
            [$etu[4], '+11 days', '09:00', $sal[1], $ens[1], $ens[0], $ens[2]],
        ];

        foreach ($soutenancesData as [$etudiant, $dateExpr, $heureStr, $salle, $president, $rapporteur, $examinateur]) {
            $soutenance = new Soutenance();
            $soutenance->setEtudiant($etudiant);
            $soutenance->setDate(new \DateTime($dateExpr));
            $soutenance->setHeure(new \DateTime($heureStr));
            $soutenance->setSalle($salle);
            $soutenance->setPresident($president);
            $soutenance->setRapporteur($rapporteur);
            $soutenance->setExaminateur($examinateur);
            $manager->persist($soutenance);
        }

        $manager->flush();
    }
}
