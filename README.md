# Meet Us

Meet US est une plateforme de réseautage social permettant de créer ou rejoindre des évènements physiques ou en ligne. Ainsi, les différents membres de l'application, peuvent se retrouver avec d'autres membres partageant les mêmes intérêts.

## Guide d'installation du projet en local

- Cloner le projet sur sa machine
- Se rendre dans le dossier qui a été créé
- Ouvrir un terminal, et se rendre sur la branche la plus à jour (sur GitHub regarder la branche ayant le plus de commits en avance sur la branche main), puis installer les dépendances du projet en ligne de commande avec :

```bash
composer install
```

- Créer à la racine du projet un fichier `.env.local`
- Copier la ligne de code ci-dessous dans votre fichier `.env.local`

```bash
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"
```

- Puis remplacer `db_user` par l'utilisateur ayant les droits sur la base de données que vous allez créer pour le projet Meet Us, `db_password` par le mot de passe de cet utilisateur, `db_name` par le nom de la base de données que vous souhaitez créer et `5.7` par la version de votre mysql (faire un `mysql --version` pour obtenir la version de votre mysql, normalement sur la VM ce sera `10.3.25-MariaDB`)

- Ouvrir un terminal et taper ces lignes de commandes une par une :

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

Après ces lignes de commandes ci-dessus, vous aurez une base de données avec de fausses données prêtes à l'emploi.

- Créer les clées publiques et privées nécessaires à la gestion du JWT (Json Web Token) avec cette ligne de commande (l'option --skip-if-exists permet de ne pas générer de clées si elles le sont déjà) :

```bash
php bin/console lexik:jwt:generate-keypair --skip-if-exists
```

- Il vous suffit désormais de lancer votre serveur php :

```bash
php -S localhost:8080 -t public/
```

- Enjoy !
