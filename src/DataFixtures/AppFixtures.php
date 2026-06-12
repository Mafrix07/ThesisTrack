<?php

namespace App\DataFixtures;

use App\Entity\AppUser;
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
        $teacher = new AppUser();
        $teacher->setEmail('prof@soutenance.pro');
        $teacher->setNom('DOE');
        $teacher->setPrenom('John');
        $teacher->setRoles(['ROLE_ENSEIGNANT']);
        $password = $this->hasher->hashPassword($teacher, 'prof123');
        $teacher->setPassword($password);
        $manager->persist($teacher);

        $manager->flush();
    }
}
