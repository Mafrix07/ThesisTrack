# 🎓 ThesisTrack

> Plateforme de gestion des soutenances de fin d'études — Symfony 6/7

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat&logo=php)
![Symfony](https://img.shields.io/badge/Symfony-6%2F7-000000?style=flat&logo=symfony)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat&logo=bootstrap)
![License](https://img.shields.io/badge/License-MIT-green?style=flat)

---

## 📋 Présentation

**ThesisTrack** est une application web développée avec **Symfony** permettant à une université d'automatiser la gestion des soutenances de fin d'études.

Elle permet de :
- Gérer les étudiants et leurs mémoires
- Gérer les enseignants et leurs spécialités
- Gérer les salles disponibles
- Organiser et programmer les soutenances
- Constituer les jurys (Président, Rapporteur, Examinateur)
- Consulter des tableaux de bord adaptés selon le rôle
- Sécuriser l'accès selon les rôles utilisateurs

---

## 👥 Utilisateurs

| Rôle | Droits |
|------|--------|
| **Administrateur** | Accès total : gestion des étudiants, enseignants, salles, soutenances, statistiques |
| **Enseignant** | Consultation de ses soutenances, jurys et étudiants associés |

---

## 🛠️ Stack Technique

| Composant | Technologie |
|-----------|-------------|
| Backend | PHP 8.x / Symfony 6 ou 7 |
| ORM | Doctrine |
| Base de données | MySQL 8.0 |
| Frontend | Twig + Bootstrap 5 |
| Sécurité | Symfony Security Bundle |
| Versioning | Git / GitHub |

---

## 🚀 Installation

### Prérequis

- PHP >= 8.1
- Composer
- MySQL >= 8.0
- Symfony CLI (recommandé)
- Node.js & npm (optionnel pour les assets)

### Étapes

```bash
# 1. Cloner le dépôt
git clone https://github.com/TON_USERNAME/ThesisTrack.git
cd ThesisTrack

# 2. Installer les dépendances PHP
composer install

# 3. Configurer l'environnement
cp .env .env.local
# Éditer .env.local et renseigner DATABASE_URL :
# DATABASE_URL="mysql://user:password@127.0.0.1:3306/thesistrack"

# 4. Créer la base de données
php bin/console doctrine:database:create

# 5. Appliquer les migrations
php bin/console doctrine:migrations:migrate

# 6. Charger les données de test (fixtures)
php bin/console doctrine:fixtures:load

# 7. Lancer le serveur de développement
symfony server:start
# ou
php -S localhost:8000 -t public/
```

L'application est accessible sur : **http://localhost:8000**

---

## 🔐 Comptes de test (Fixtures)

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | admin@thesistrack.com | Admin@1234 |
| Enseignant | enseignant@thesistrack.com | Enseignant@1234 |

---

## 📁 Structure du projet

```
ThesisTrack/
├── config/                  # Configuration Symfony
├── migrations/              # Migrations Doctrine
├── src/
│   ├── Controller/          # Contrôleurs
│   ├── Entity/              # Entités Doctrine
│   │   ├── User.php
│   │   ├── Etudiant.php
│   │   ├── Enseignant.php
│   │   ├── Salle.php
│   │   └── Soutenance.php
│   ├── Form/                # FormTypes Symfony
│   ├── Repository/          # Repositories Doctrine
│   └── Security/            # Voter / Auth
├── templates/               # Vues Twig
│   ├── admin/
│   ├── enseignant/
│   └── base.html.twig
├── public/                  # Point d'entrée public
├── .env                     # Variables d'environnement
├── composer.json
└── README.md
```

---

## 🗂️ Modules

### Module 1 — Authentification & Sécurité
- Connexion / Déconnexion
- Gestion des rôles (ROLE_ADMIN, ROLE_ENSEIGNANT)
- Protection des routes selon le rôle

### Module 2 — Gestion des Étudiants
- CRUD complet
- Recherche par nom
- Email unique obligatoire

### Module 3 — Gestion des Enseignants
- CRUD complet
- Lié au compte utilisateur pour la connexion

### Module 4 — Gestion des Salles
- CRUD complet
- Code salle unique, capacité > 0

### Module 5 — Gestion des Soutenances
- Programmation avec jury (Président, Rapporteur, Examinateur)
- Détection automatique des conflits de salle et de jury
- Un étudiant = une seule soutenance

---

## 📊 Tableaux de bord

**Admin :** nombre total d'étudiants, enseignants, salles, soutenances — affichés en cartes.

**Enseignant :** nombre de jurys, liste de ses soutenances, étudiants concernés.

---

## 🔀 Développement par parties (Git)

| Commit | Contenu |
|--------|---------|
| `PARTIE 1 — Auth & Setup ✅` | Projet Symfony, entité User, login/logout, sécurité |
| `PARTIE 2 — Étudiants & Enseignants ✅` | CRUD Étudiant & Enseignant, validation |
| `PARTIE 3 — Salles ✅` | CRUD Salle, règles métier |
| `PARTIE 4 — Soutenances ✅` | Programmation, jurys, conflits |
| `PARTIE 5 — Dashboard & Finitions ✅` | Tableaux de bord, menus, UI finale |

---

## 📄 Livrables

- ✅ Application fonctionnelle
- ✅ Documentation technique (PDF)
- ✅ Guide utilisateur (PDF)
- ✅ Dépôt Git complet

---

## 👨‍💻 Auteur

**[TON NOM COMPLET]**
Filière : GL / WIM — Licence 2
Année universitaire : 2025–2026
Examen Final — IT232

---

## 📝 Licence

Ce projet est réalisé dans le cadre d'un examen académique.
