# HardWareHouse

### Description du projet

HardWareHouse (Vente de composants informatiques) - Challenge Stack semestriel : Symfony / UX UI / Intégration

### Lancement du projet
- Renommer le fichier `.env.dist` en `.env`
- Exécuter `docker compose build --pull --no-cache` pour construire les images
- Lancer `docker compose up -d` (sans les logs) / `docker compose up` (avec les logs)
- Ouvrir le navigateur sous l'adresse `https://localhost`

### Setup BDD 

1. Si la BDD n'est pas créée, faites la commande : php bin/console doctrine:database:create
2. Ensuite php bin/console doctrine:migrations:migrate
3. Pour charger les fixtures : php bin/console doctrine:fixture:load

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
