# Meet Us

Meet US est une plateforme de réseautage social permettant de créer ou rejoindre des évènements physiques ou en ligne. Ainsi, les différents membres de l'application, peuvent se retrouver avec d'autres membres partageant les mêmes intérêts.

## Guide d'installation du projet en local

- Cloner le projet sur sa machine
- Se rendre dans le dossier qui a été créé
- Installer les dépendances du projet en ligne de commande avec :

```shell
composer install
```

- Créer à la racine du projet un fichier `.env.local`
- Copier la ligne de code ci-dessous dans votre fichier `.env.local`

```shell
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"
```

- Puis remplacer `db_user` par l'utilisateur ayant les droits sur la base de données que vous allez créer pour le projet Meet Us, `db_password` par le mot de passe de cet utilisateur, `db_name` par le nom de la base de données que vous souhaitez créer et `5.7` par la version de votre mysql (faire un `mysql --version` pour obtenir la version de votre mysql)

- Ouvrez un terminal et tapez ces lignes de commandes une par une :

```shell
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

- Après ces 3 lignes de commandes ci-dessus, vous aurez une base de données avec des fausses données prêtes à l'emploi, il vous suffit désormais de lancer votre serveur php :

```shell
php -S localhost:8080 -t public/
```

- Enjoy !
