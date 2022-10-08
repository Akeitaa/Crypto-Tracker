######REQUIRED#####
VERSION PHP >= 8.1
VERSION SYMFONY >= 6.1.5
SYMFONY CLI
######REQUIRED#####

#INSTALLATION
0.Allumer votre Serveur 
C'est-à-dire : MAMP ou XAMP ou WAMP ou LAMP.

1.Installer les dépendances de composer
Dans le terminal, taper la commande : composer install.

2.Installer les dépendances de Npm
Dans le terminal, taper la commande : npm install.

3.Configurer la bdd
Configurer dans le .env et le .env.test, un accès à la base de données.

4.Créer une base de données
Dans le terminal, taper la commande : symfony console d:d:c

5.Créer une base de données de test
Dans le terminal, taper la commande : symfony console d:d:c --env=test

6.Créer un fichier de migration
Dans le terminal, taper la commande : symfony console make:migration

7.Jouer le fichier de migration
Dans le terminal, taper la commande : symfony console d:m:m

8.Configurer le schéma de la bdd de test
Dans le terminal, taper la commande : symfony console d:s:u --env=test --force

9.Load les fixtures de dev et de test
Dans le terminal, taper la commande : symfony console doctrine:fixtures:load --no-interaction
Puis
Dans le terminal, taper la commande : symfony console doctrine:fixtures:load --env=test --no-interaction

10.Démarrer le serveur symfony
Dans le terminal, taper la commande : symfony serve

11.Compiler avec webpack
Dans un autre terminal, taper : npm run build

12.Rendez vous sur : localhost:8000
Welcome to Crypto Tracker :)

#TEST INTEGRATION
Pour jouer les tests d'intégration, dans le terminal taper : php bin/phpunit
