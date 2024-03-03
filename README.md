# HardWareHouse

### Description du projet

HardWareHouse (Vente de composants informatiques) - Challenge Stack semestriel : Symfony / UX UI / Intégration

### Lancement du projet

- Renommer le fichier `.env.dist` en `.env`
- Exécuter `docker compose build --pull --no-cache` pour construire les images
- Lancer `docker compose up -d` (sans les logs) / `docker compose up` (avec les logs)
- Ouvrir le navigateur sous l'adresse `https://localhost`

### Setup BDD

1. Si la BDD n'est pas créée, faites la commande : docker compose exec php bin/console doctrine:database:create
2. Ensuite docker compose exec php bin/console doctrine:migrations:migrate
3. Pour charger les fixtures : docker compose exec php bin/console doctrine:fixture:load

# Installation npm

1. docker compose exec php npm install
2. docker compose exec php npm run dev

### Membres du groupe

Sami ASSIAKH - GitHub : iSaaMz - https://github.com/iSaaMz

Jay BURY - GitHub : buryj97 - https://github.com/buryj97

Aria AMAN - GitHub : AriaAman - https://github.com/AriaAman

Moussa Seydou TRAORE - GitHub : MoussaST - https://github.com/MoussaST

### Répo du projet

Lien : https://github.com/HardWareHouse/HardWareHouse


# Tâches réalisées

### Attribution des tâches : 

### Sami : 

Front login/register/sidebar

Création de la base de données et liaison des entitées

Résolution de divers problèmes liées à l’ensemble des entitées

Fix problèmes liées aux uuid

Front fusion sidebar et mainpage

Ajout DataTables aux CRUDS + Traduction Française + Front + Filtre de recherches

Personnalisation de l’ensemble des CRUDS

DataFixtures (Ajout d’un jeu de données de test)

Gestion utilisateur comptable

Edition des FormType

Import d’image avec VichUploader pour les logos d’Entreprises

Conditionnement des formulaires, contraintes de validations (validators, asserts, regex)

Automatisation date/heure création produits et catégories

Ajouts nouveaux champs + détaillées pour les entreprises et clients

Gestion des PDF Devis et Facture (affichage logo, données)

Gestion calcul TVA Devis et Facture

Listing des Produits dans les Devis et Catégories

Auto Incrementation des createdAt



### Aria : 

Authentification (Connexion/Inscription) Partie Back-End

Envoie de Mail lors de l’Inscription Partie Back-End et Front-End

Envoie de Mail pour vérifier le User Partie Back-End et Front-End

Création de la base de données avec les relations

Création des CRUD Partie Back-End et Front-End 

SideBar Front et Back 

Gestions des rôles

Génération de PDF Partie Back-End et Front-End

Envoie de mail lors de la création de Devis et Facture

Ajout de la partie Reset-Password

Ajout des pages d’erreurs

Modification du docker-compose.override pour le paramétrage du Mailer

Envoie de Mail via un SMTP (Brevo) sur n’importe quel email 

Mise en production du site en ligne via un VPS (DigitalOcean) 

Set-up du nom de domaine 

Rendre l’application responsive

Ajout des thèmes Dark-Mode, Forest-Mode, Love-Mode

Page error 404, 500 et le reste des erreurs 

Installation et configurations du premier Echart.js 

Créations d'un Actions pour le CD (continuous deployment)

### Moussa :

Crud User 

Crud Admin

Front Admin

Back Admin (Crud Admin et home Page )

Correction et Amélioration Controller 

Correction et Amélioration Entité User et entreprise

Maquette Authentification

Création base de données

Sécurité Back Admin

Gestion des Rôles

Sécurité de l'accès des données dans les CRUD

Interface Admin

Composants Dashboard Admin 

Création de devis

Conversion devis en facture de manière  automatique

Gestion des rôles

Gestion stocks de produits

Accessibilité des données selon si c’est une entreprise ou un admin 

Accessibilité des routes selon si c’est une entreprise ou un admin

Auto-incrémentation des numéro de Devis

Vérification du compte utilisateur avant login 



### Jay :

Charte graphique (logo, couleurs)

Organisation de la gestion de projet (Notion / Trello)

Maquette page d’accueil utilisateur

Front page d’accueil utilisateur

Back page d’accueil utilisateur (traitement des données avec Symfony)

Création des fonctions dans les repositories pour filtrer les données

Installation du système de traduction et changement entre les langues

Traduction français -> anglais

Composants Dashboard Admin 

Fonctionnalité de rapport financier/globaux 

Création des charts avec eCharts

Filtres année / entreprise 

Traitement des données en Symfony

Génération automatique des CSVs par année / entreprise

Données différentes pour admin et comptable

Validation de mot de passe lors de l’inscription

