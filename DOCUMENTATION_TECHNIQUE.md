<div style="font-family: 'Trebuchet MS', Trebuchet, sans-serif; color: #000000; line-height: 1.75; max-width: 900px; margin: 0 auto; padding: 40px 24px;">

---

<h1 style="font-family: 'Trebuchet MS', Trebuchet, sans-serif; color: #002366; font-size: 2.4em; border-bottom: 3px solid #002366; padding-bottom: 10px; margin-bottom: 6px;">
  Documentation Technique
</h1>

<p style="font-family: 'Trebuchet MS', Trebuchet, sans-serif; color: #002366; font-size: 1.1em; font-weight: bold; margin-top: 0;">
  SoutenancePro — Gestion des Soutenances de Fin d'Études
</p>

<table style="font-family: 'Trebuchet MS', Trebuchet, sans-serif; font-size: 0.95em; border-collapse: collapse; margin-top: 20px;">
  <tr><td style="padding: 4px 16px 4px 0; color: #555;"><strong>Auteur</strong></td><td>Mario D'ALMEIDA</td></tr>
  <tr><td style="padding: 4px 16px 4px 0; color: #555;"><strong>Filière</strong></td><td>GL / WIM — Licence 2</td></tr>
  <tr><td style="padding: 4px 16px 4px 0; color: #555;"><strong>Année universitaire</strong></td><td>2025 – 2026</td></tr>
  <tr><td style="padding: 4px 16px 4px 0; color: #555;"><strong>Matière</strong></td><td>IT 232 — Développement Web II</td></tr>
  <tr><td style="padding: 4px 16px 4px 0; color: #555;"><strong>Dépôt Git</strong></td><td><a href="https://github.com/Mafrix07/ThesisTrack" style="color: #002366;">https://github.com/Mafrix07/ThesisTrack</a></td></tr>
</table>

---

## <span style="color: #002366;">1. Présentation du projet</span>

<p><strong style="color: #002366;">ThesisTrack</strong> est une application web développée avec le framework <strong style="color: #002366;">Symfony 8.1</strong> permettant à une université d'automatiser la gestion des soutenances de fin d'études.</p>

L'application couvre l'intégralité du cycle de vie d'une soutenance :

- Enregistrement des **étudiants** et de leurs mémoires
- Gestion du **corps enseignant** et de leurs spécialités
- Administration des **salles** disponibles
- **Programmation des soutenances** avec constitution du jury
- **Détection automatique des conflits** de salle et de jury
- **Tableaux de bord** adaptés selon le rôle de l'utilisateur
- **Contrôle d'accès** strict par rôles

---

## <span style="color: #002366;">2. Stack technique</span>

<table style="font-family: 'Trebuchet MS', Trebuchet, sans-serif; border-collapse: collapse; width: 100%;">
  <thead>
    <tr style="background-color: #002366; color: white;">
      <th style="padding: 10px 16px; text-align: left;">Composant</th>
      <th style="padding: 10px 16px; text-align: left;">Technologie</th>
      <th style="padding: 10px 16px; text-align: left;">Version</th>
    </tr>
  </thead>
  <tbody>
    <tr style="background:#f8f8f8;"><td style="padding:8px 16px;">Langage backend</td><td style="padding:8px 16px;"><strong>PHP</strong></td><td style="padding:8px 16px;">8.4</td></tr>
    <tr><td style="padding:8px 16px;">Framework</td><td style="padding:8px 16px;"><strong>Symfony</strong></td><td style="padding:8px 16px;">8.1</td></tr>
    <tr style="background:#f8f8f8;"><td style="padding:8px 16px;">ORM</td><td style="padding:8px 16px;"><strong>Doctrine ORM</strong></td><td style="padding:8px 16px;">3.x</td></tr>
    <tr><td style="padding:8px 16px;">Base de données</td><td style="padding:8px 16px;"><strong>PostgreSQL</strong></td><td style="padding:8px 16px;">16</td></tr>
    <tr style="background:#f8f8f8;"><td style="padding:8px 16px;">Moteur de templates</td><td style="padding:8px 16px;"><strong>Twig</strong></td><td style="padding:8px 16px;">3.x</td></tr>
    <tr><td style="padding:8px 16px;">CSS Framework</td><td style="padding:8px 16px;"><strong>Bootstrap</strong></td><td style="padding:8px 16px;">5.3</td></tr>
    <tr style="background:#f8f8f8;"><td style="padding:8px 16px;">Design personnalisé</td><td style="padding:8px 16px;"><strong>CSS custom</strong> (Outfit + Bebas Neue)</td><td style="padding:8px 16px;">—</td></tr>
    <tr><td style="padding:8px 16px;">Sécurité</td><td style="padding:8px 16px;"><strong>Symfony Security Bundle</strong></td><td style="padding:8px 16px;">8.1</td></tr>
    <tr style="background:#f8f8f8;"><td style="padding:8px 16px;">Versioning</td><td style="padding:8px 16px;"><strong>Git / GitHub</strong></td><td style="padding:8px 16px;">—</td></tr>
  </tbody>
</table>

---

## <span style="color: #002366;">3. Architecture du projet</span>

Le projet suit l'architecture **MVC (Modèle – Vue – Contrôleur)** imposée par Symfony :

```
ThesisTrack/
├── config/                     # Configuration Symfony (security, doctrine, routing)
├── migrations/                 # Migrations Doctrine (4 migrations)
├── public/
│   ├── index.php               # Point d'entrée unique
│   └── css/thesistrack.css     # Feuille de style personnalisée
├── src/
│   ├── Controller/             # Contrôleurs HTTP
│   │   ├── SecurityController.php
│   │   ├── DashboardController.php
│   │   ├── EtudiantController.php
│   │   ├── EnseignantController.php
│   │   ├── SalleController.php
│   │   └── SoutenanceController.php
│   ├── Entity/                 # Entités Doctrine (modèles)
│   │   ├── AppUser.php
│   │   ├── Etudiant.php
│   │   ├── Enseignant.php
│   │   ├── Salle.php
│   │   └── Soutenance.php
│   ├── Form/                   # FormTypes Symfony
│   ├── Repository/             # Requêtes DQL personnalisées
│   └── DataFixtures/           # Données de test
├── templates/                  # Vues Twig
│   ├── base.html.twig
│   ├── _navbar.html.twig
│   ├── dashboard/
│   ├── security/
│   ├── etudiant/
│   ├── enseignant/
│   ├── salle/
│   └── soutenance/
└── tests/                      # Tests fonctionnels
    └── SecurityTest.php
```

---

## <span style="color: #002366;">4. Diagramme de classes UML</span>

```
┌──────────────────────────────┐        ┌──────────────────────────────┐
│           AppUser            │        │          Etudiant            │
├──────────────────────────────┤        ├──────────────────────────────┤
│ - id        : int            │        │ - id          : int          │
│ - email     : string (UNIQ)  │        │ - nom         : string       │
│ - nom       : string         │        │ - prenom      : string       │
│ - prenom    : string         │        │ - email       : string (UNIQ)│
│ - roles     : array          │        │ - filiere     : string       │
│ - password  : string (hash)  │        │ - themeMemoire: string       │
├──────────────────────────────┤        ├──────────────────────────────┤
│ + getUserIdentifier()        │        │ + getId()                    │
│ + getRoles()                 │        │ + getNom()                   │
│ + getPassword()              │        │ + getEmail()                 │
└──────────┬───────────────────┘        └──────────────┬───────────────┘
           │ 1                                         │ 1
           │ OneToOne                                  │ OneToOne
           │ 0..1                                      │ 0..1
┌──────────▼───────────────────┐        ┌──────────────▼───────────────┐
│          Enseignant           │        │          Soutenance          │
├──────────────────────────────┤        ├──────────────────────────────┤
│ - id         : int           │        │ - id          : int          │
│ - nom        : string        │◄───────│ - president   : Enseignant   │
│ - prenom     : string        │◄───────│ - rapporteur  : Enseignant   │
│ - email      : string (UNIQ) │◄───────│ - examinateur : Enseignant   │
│ - specialite : string        │        │ - etudiant    : Etudiant     │
│ - user       : AppUser       │        │ - salle       : Salle        │
├──────────────────────────────┤        │ - date        : date         │
│ + __toString()               │        │ - heure       : time         │
└──────────────────────────────┘        ├──────────────────────────────┤
                                        │ + validateJury()             │
┌──────────────────────────────┐        └──────────────────────────────┘
│            Salle             │                     ▲
├──────────────────────────────┤                     │ ManyToOne
│ - id          : int          │─────────────────────┘
│ - code        : string (UNIQ)│
│ - capacite    : int          │
│ - localisation: string       │
├──────────────────────────────┤
│ + __toString()               │
└──────────────────────────────┘
```

<p style="font-size: 0.9em; color: #555;">
  Note : Les flèches <code>◄───</code> représentent des relations ManyToOne depuis Soutenance vers Enseignant (trois relations distinctes : président, rapporteur, examinateur).
</p>

---

## <span style="color: #002366;">5. Description des entités</span>

### <span style="color: #002366;">5.1 AppUser</span>

Entité de sécurité implémentant `UserInterface` et `PasswordAuthenticatedUserInterface` de Symfony. Elle représente un compte utilisateur dans le système.

| Champ | Type SQL | Contraintes |
|-------|----------|-------------|
| `id` | `INT` (PK, auto) | — |
| `email` | `VARCHAR(180)` | `UNIQUE`, `NOT NULL` |
| `nom` | `VARCHAR(255)` | `NOT NULL` |
| `prenom` | `VARCHAR(255)` | `NOT NULL` |
| `roles` | `JSON` | `NOT NULL` — minimum `ROLE_USER` |
| `password` | `VARCHAR(255)` | `NOT NULL` — bcrypt hashé |

> <span style="color: #002366;"><strong>Rôles disponibles :</strong></span> `ROLE_ADMIN` (droits complets) · `ROLE_ENSEIGNANT` (lecture seule de ses données) · `ROLE_USER` (attribué automatiquement à tous)

---

### <span style="color: #002366;">5.2 Etudiant</span>

Représente un étudiant soumis à une soutenance. Non lié à un compte utilisateur — il est géré exclusivement par l'administrateur.

| Champ | Type SQL | Contraintes |
|-------|----------|-------------|
| `id` | `INT` (PK, auto) | — |
| `nom` | `VARCHAR(255)` | `NOT NULL` |
| `prenom` | `VARCHAR(255)` | `NOT NULL` |
| `email` | `VARCHAR(180)` | `UNIQUE`, `NOT NULL`, format email valide |
| `filiere` | `VARCHAR(255)` | `NOT NULL` |
| `theme_memoire` | `VARCHAR(255)` | `NOT NULL` |

---

### <span style="color: #002366;">5.3 Enseignant</span>

Représente un membre du corps enseignant pouvant participer aux jurys. Il est **optionnellement lié** à un compte `AppUser` pour lui permettre de se connecter.

| Champ | Type SQL | Contraintes |
|-------|----------|-------------|
| `id` | `INT` (PK, auto) | — |
| `nom` | `VARCHAR(255)` | `NOT NULL` |
| `prenom` | `VARCHAR(255)` | `NOT NULL` |
| `email` | `VARCHAR(180)` | `UNIQUE`, `NOT NULL`, format email valide |
| `specialite` | `VARCHAR(255)` | `NOT NULL` |
| `user_id` | `INT` (FK) | `NULLABLE` → `app_user.id` |

---

### <span style="color: #002366;">5.4 Salle</span>

Représente une salle disponible pour l'organisation des soutenances.

| Champ | Type SQL | Contraintes |
|-------|----------|-------------|
| `id` | `INT` (PK, auto) | — |
| `code` | `VARCHAR(50)` | `UNIQUE`, `NOT NULL` |
| `capacite` | `INT` | `NOT NULL`, doit être `> 0` |
| `localisation` | `VARCHAR(255)` | `NOT NULL` |

---

### <span style="color: #002366;">5.5 Soutenance</span>

Entité centrale du système. Elle regroupe toutes les informations d'une session de soutenance.

| Champ | Type SQL | Contraintes |
|-------|----------|-------------|
| `id` | `INT` (PK, auto) | — |
| `etudiant_id` | `INT` (FK) | `NOT NULL`, `UNIQUE` → `etudiant.id` |
| `president_id` | `INT` (FK) | `NOT NULL` → `enseignant.id` |
| `rapporteur_id` | `INT` (FK) | `NOT NULL` → `enseignant.id` |
| `examinateur_id` | `INT` (FK) | `NOT NULL` → `enseignant.id` |
| `salle_id` | `INT` (FK) | `NOT NULL` → `salle.id` |
| `date` | `DATE` | `NOT NULL` |
| `heure` | `TIME` | `NOT NULL` |

> <span style="color: #002366;"><strong>Règle métier critique :</strong></span> La méthode `validateJury()` (callback Symfony Validator) vérifie qu'un même enseignant n'occupe pas deux rôles différents dans le même jury.

---

## <span style="color: #002366;">6. Description des relations</span>

### <span style="color: #002366;">6.1 AppUser ↔ Enseignant (OneToOne bidirectionnel)</span>

Un `Enseignant` peut être lié à **au plus un** `AppUser`. Cette relation est optionnelle — un enseignant peut exister dans la base sans avoir de compte de connexion.

```
AppUser  1 ──────── 0..1  Enseignant
```

> Cascade `persist` et `remove` côté `Enseignant` : supprimer l'enseignant supprime son compte utilisateur associé.

---

### <span style="color: #002366;">6.2 Etudiant ↔ Soutenance (OneToOne implicite)</span>

La contrainte `@UniqueEntity(['etudiant'])` sur `Soutenance` garantit qu'**un étudiant ne peut avoir qu'une seule soutenance** enregistrée dans le système.

```
Etudiant  1 ──────── 0..1  Soutenance
```

---

### <span style="color: #002366;">6.3 Enseignant ↔ Soutenance (ManyToOne × 3)</span>

Trois relations `ManyToOne` distinctes relient `Soutenance` à `Enseignant`, correspondant aux trois rôles du jury :

```
Enseignant  1 ◄──── *  Soutenance  (champ : president)
Enseignant  1 ◄──── *  Soutenance  (champ : rapporteur)
Enseignant  1 ◄──── *  Soutenance  (champ : examinateur)
```

> Un même enseignant peut être président d'une soutenance et rapporteur d'une autre, mais **pas deux rôles dans le même jury** (validé par callback) et **pas deux jurys à la même heure** (détecté au niveau du contrôleur).

---

### <span style="color: #002366;">6.4 Salle ↔ Soutenance (ManyToOne)</span>

Une salle peut accueillir plusieurs soutenances (à des dates/heures différentes), mais **une salle ne peut pas accueillir deux soutenances simultanées**.

```
Salle  1 ◄──── *  Soutenance
```

> La vérification de conflit est effectuée dans `SoutenanceController::hasConflicts()` via une requête DQL.

---

## <span style="color: #002366;">7. Sécurité et contrôle d'accès</span>

### <span style="color: #002366;">7.1 Mécanisme d'authentification</span>

Symfony Security Bundle est configuré avec :
- **Formulaire de connexion** : route `/login`, champ identifiant = `email`
- **Hashage des mots de passe** : `bcrypt` via `UserPasswordHasherInterface`
- **Déconnexion** : route `/logout`
- **Pare-feu** : toute route non-login redirige vers `/login` si non authentifié

### <span style="color: #002366;">7.2 Hiérarchie des rôles</span>

```
ROLE_ADMIN
    └── ROLE_ENSEIGNANT
            └── ROLE_USER
```

> <span style="color: #002366;"><strong>Note :</strong></span> `ROLE_ADMIN` hérite de tous les droits de `ROLE_ENSEIGNANT` et `ROLE_USER`.

### <span style="color: #002366;">7.3 Protection des routes</span>

| Route | Accès requis |
|-------|-------------|
| `/login` | Public |
| `/` (dashboard) | `ROLE_USER` |
| `/admin/etudiant/*` | `ROLE_ADMIN` |
| `/admin/enseignant/*` | `ROLE_ADMIN` |
| `/admin/salle/*` | `ROLE_ADMIN` |
| `/admin/soutenance/*` | `ROLE_ADMIN` |

Protection appliquée via l'attribut PHP `#[IsGranted('ROLE_ADMIN')]` sur les contrôleurs concernés.

### <span style="color: #002366;">7.4 Protection CSRF</span>

Tous les formulaires de suppression utilisent des **tokens CSRF** générés par Symfony (`csrf_token()`) et validés côté contrôleur avec `isCsrfTokenValid()`.

---

## <span style="color: #002366;">8. Règles métier et validations</span>

### <span style="color: #002366;">8.1 Validation des entités (Symfony Validator)</span>

| Entité | Règle | Contrainte utilisée |
|--------|-------|---------------------|
| `Etudiant` | Email unique | `@UniqueEntity` + `@Email` |
| `Etudiant` | Tous les champs obligatoires | `@NotBlank` |
| `Enseignant` | Email unique et valide | `@UniqueEntity` + `@Email` |
| `Salle` | Code unique | `@UniqueEntity` |
| `Salle` | Capacité > 0 | `@Positive` |
| `Soutenance` | Un étudiant = une soutenance | `@UniqueEntity(['etudiant'])` |
| `Soutenance` | Date ≥ aujourd'hui (création uniquement) | `@GreaterThanOrEqual('today', groups: ['creation'])` |
| `Soutenance` | Jury sans doublons | `@Callback` → `validateJury()` |

### <span style="color: #002366;">8.2 Détection de conflits (niveau contrôleur)</span>

La méthode privée `SoutenanceController::hasConflicts()` exécute deux vérifications avant chaque enregistrement :

**Conflit de salle** : requête DQL comptant les soutenances ayant la même `date`, `heure` et `salle` (en excluant l'entité courante lors d'une modification).

**Conflit de jury** : pour chacun des trois membres du jury, requête DQL comptant les soutenances à la même `date` et `heure` où cet enseignant apparaît (président, rapporteur ou examinateur).

En cas de conflit, un **flash message d'erreur** est ajouté et le formulaire est re-affiché.

---

## <span style="color: #002366;">9. Tableaux de bord</span>

### <span style="color: #002366;">9.1 Dashboard Administrateur</span>

Affiche quatre **cartes KPI** cliquables renvoyant vers la liste correspondante :

| Carte | Donnée affichée | Destination |
|-------|----------------|-------------|
| Étudiants | `COUNT(etudiant)` | `/admin/etudiant/` |
| Enseignants | `COUNT(enseignant)` | `/admin/enseignant/` |
| Salles | `COUNT(salle)` | `/admin/salle/` |
| Soutenances | `COUNT(soutenance)` | `/admin/soutenance/` |

### <span style="color: #002366;">9.2 Dashboard Enseignant</span>

L'enseignant connecté voit **uniquement ses données** :

- Nombre de jurys auxquels il participe
- Tableau des étudiants concernés (nom, prénom, filière, thème)
- Tableau de ses soutenances programmées (date, heure, salle, son rôle)

La résolution de l'enseignant connecté est faite via :
```php
$enseignant = $enseignantRepository->findOneBy(['user' => $this->getUser()]);
```

---

## <span style="color: #002366;">10. Requêtes personnalisées (Repository)</span>

### <span style="color: #002366;">SoutenanceRepository</span>

```php
// Recherche des soutenances par date
public function findByDate(\DateTimeInterface $date): array

// Recherche des soutenances où l'enseignant participe (tous rôles)
public function findByEnseignant($enseignant): array
```

### <span style="color: #002366;">EtudiantRepository</span>

```php
// Recherche d'étudiants par nom ou prénom (LIKE insensible à la casse)
public function searchByNom(string $query): array
```

---

## <span style="color: #002366;">11. Guide d'installation</span>

### Prérequis

- PHP >= 8.1
- Composer
- PostgreSQL >= 14
- Symfony CLI (recommandé)

### Étapes

```bash
# 1. Cloner le dépôt
git clone https://github.com/Mafrix07/ThesisTrack.git
cd ThesisTrack

# 2. Installer les dépendances
composer install

# 3. Configurer la base de données
cp .env .env.local
# Éditer .env.local :
# DATABASE_URL="postgresql://user:password@127.0.0.1:5432/thesistrack?serverVersion=16&charset=utf8"

# 4. Créer la base et appliquer les migrations
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# 5. Charger les données de test
php bin/console doctrine:fixtures:load

# 6. Lancer le serveur
symfony server:start
```

Application accessible sur : **http://localhost:8000**

### Comptes de test

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | `admin@soutenance.pro` | `admin123` |
| Enseignant 1 | `prof1@soutenance.pro` | `prof123` |
| Enseignant 2 | `prof2@soutenance.pro` | `prof123` |
| Enseignant 3 | `prof3@soutenance.pro` | `prof123` |

---

## <span style="color: #002366;">12. Tests</span>

Le projet inclut des tests fonctionnels via `WebTestCase` de Symfony :

```php
// tests/SecurityTest.php

// Vérifie la redirection vers /login pour un utilisateur non authentifié
testRedirectToLogin()

// Vérifie que la connexion fonctionne avec les identifiants corrects
testLoginWorks()
```

Exécution des tests :

```bash
php bin/phpunit
```

---

## <span style="color: #002366;">13. Développement par étapes (historique Git)</span>

| Commit | Contenu |
|--------|---------|
| `PARTIE 1 — Auth & Setup` | Projet Symfony, entité `AppUser`, login/logout, sécurité |
| `PARTIE 2 — Étudiants & Enseignants` | CRUD `Etudiant` & `Enseignant`, validation, recherche par nom |
| `PARTIE 3 — Salles` | CRUD `Salle`, règles métier (code unique, capacité > 0) |
| `PARTIE 4 — Soutenances` | Programmation, constitution du jury, détection de conflits |
| `PARTIE 5 — Dashboard & Finitions` | Tableaux de bord adaptatifs, refonte UI, tests, README |

---

## <span style="color: #002366;">14. Lien Git du projet</span>

<p style="font-size: 1.05em;">
  <strong style="color: #002366;">Dépôt GitHub :</strong>
  <a href="https://github.com/Mafrix07/ThesisTrack" style="color: #002366; font-weight: bold;">
    https://github.com/Mafrix07/ThesisTrack
  </a>
</p>

---

<p style="font-size: 0.85em; color: #888; text-align: center; margin-top: 40px;">
  Document réalisé dans le cadre de l'examen final IT 232 — Développement Web II · Année universitaire 2025–2026
</p>

</div>
