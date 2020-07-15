## Création des entités :
1. MediaType
2. Role
3. Staff
4. Member
5. Media
6. Book
7. Music
8. Film
9. StockableMedia
10. DigitalMedia
11. Borrow
12. StockableMediaState

Créer les entités via la console de Symfony :
```bash
cd /path/to/project
./bin/console make:entity
```
Cela lance un "dialogue" avec Symfony, qui demandera d'abord le nom de l'entité à créer (par exemple, MediaType).
Puis les champs (ou propriétés de classe) à renseigner : nom du champ, type (entier, chaîne de charactères, "relation", etc.), la longueur de la chaîne (si c'est une chaîne),
    champ "nullable" (c'est-à-dire, obligatoire ou non)
Dans le cas d'une relation, il faut spécifier s'il s'agit d'une relation de type "OneToOne", "OneToMany", "ManyToMany" ou "ManyToOne".
Dans la plupart des cas, lorsqu'il s'agit de placer une clé étrangère dans une autre entité, on renseigne à partir de cette autre entité, la relation "ManyToOne".
Symfony demandera à quelle classe la clé fait référence et s'il faut créer une fonction du type "getOtherEntities()" dans la classe dont on a fait la référence.
Par exemple, nous souhaitons placer la clé étrangère "media_type_id" dont la référence est "id" de la classe "MediaType",
dans la classe "Media".
```bash
cd /path/to/project
./bin/console make:entity
 Class name of the entity to create or update (e.g. GrumpyGnome):
 > Media
 Your entity already exists! So let's add some new fields!

 New property name (press <return> to stop adding fields):
 > media_type

 Field type (enter ? to see all types) [string]:
 > ManyToOne

 What class should this entity be related to?:
 > MediaType

 Is the Media.media_type property allowed to be null (nullable)? (yes/no) [yes]:
 > no

 Do you want to add a new property to MediaType so that you can access/update Media objects from it - e.g. $mediaType->getMedia()? (yes/no) [yes]:
 > yes  # Ici on veut créer une fonction dans MediaType qui permettra de lister tous les médias d'un type de média

 A new property will also be added to the MediaType class so that you can access the related Media objects from it.

 New field name inside MediaType [media]:
 > medias  # la fonction pour récupérer les médias d'un type s'appellera "getMedias()"

 Do you want to activate orphanRemoval on your relationship?
 A Media is "orphaned" when it is removed from its related MediaType.
 e.g. $mediaType->removeMedia($media)

 NOTE: If a Media may *change* from one MediaType to another, answer "no".

 Do you want to automatically delete orphaned App\Entity\Media objects (orphanRemoval)? (yes/no) [no]:
 > no  # Ici, je mets non parce que je ne souhaite pas utiliser une suppression en "cascade", i.e si on supprime un type = tous les médias de ce type vont être aussi supprimés (ici, la suppression n'est pas permise)

 updated: src/Entity/Media.php
 updated: src/Entity/MediaType.php

```

## Création de la DB à partir des entités Doctrine définies dans src/Entity

# S'assurer que la configuration de la base de données est faite dans le fichier ".env.local"
S'il n'existe pas, copier le fichier ".env" et le coller en le renommant ".env.local". Ce dernier sera alors utilisé en priorité par Symfony

Vérifier les paramètres de connexion à la DB, normalement, à la fin du fichier
```.env
DATABASE_URL=mysql://[db_user]:[db_password]@127.0.0.1:3306/mediatheque_3wa?serverVersion=5.7
```
Remplacer "db_user" par son utilisateur MySQL ayant le droit de créer une database (par exemple "root").

Pour fini, utiliser la console symfony :
```bash
cd /path/to/project
# Si l'on souhaite d'abord supprimer une base existante :
./bin/console doctrine:database:drop
# Puis création
./bin/console doctrine:database:create
# Au cas où il y aurait déjà des fichiers précédents de migration
rm -f src/Migrations/Version*.php
# Compilation de la migration
./bin/console make:migration
# Migration (création des tables en BDD)
./bin/console doctrine:migrations:migrate
```
