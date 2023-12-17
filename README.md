# 0HardWareHouse

### Description du projet

HardWareHouse (Vente de composants informatiques) - Challenge Stack semestriel : Symfony / UX UI / Intégration

### Lancement du projet

- Exécuter `docker compose build --pull --no-cache` pour construire les images

### Membres du groupe

Sami ASSIAKH - GitHub : iSaaMz - https://github.com/iSaaMz

Jay BURY - GitHub : buryj97 - https://github.com/buryj97

Aria AMAN - GitHub : AriaAman - https://github.com/AriaAman 

Moussa Seydou TRAORE - GitHub : MoussaST - https://github.com/MoussaST

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/)
2. Run `docker compose build --pull --no-cache` to build fresh images
3. Run `docker compose up` (the logs will be displayed in the current shell) or Run `docker compose up -d` to run in background 
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `docker compose down --remove-orphans` to stop the Docker containers.
6. Run `docker compose logs -f` to display current logs, `docker compose logs -f [CONTAINER_NAME]` to display specific container's current logs 


# Installation 

1. docker compose exec php npm install
2. docker compose exec php npm run dev (Nous préconisons de faire cette commande à chaque git pull pour être sûr d'avoir les dernière modifications côté front.)
3. docker compose exec php php bin/console doctrine:fixture:load (pour avoir une base de données déjà remplie)

# SETUP BDD 

1. Si la BDD n'est pas créée, faites la commande : php bin/console doctrine:database:create
2. Ensuite php bin/console doctrine:migrations:migrate