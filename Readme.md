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

Pour finir, utiliser la console symfony :
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



# Créer la sécurité et la page de login, pour les 2 types d'utilisateurs (Member & Staff)

D'abord, il faut mentionner que le point clé de la gestion de la sécurité sous Symfony, c'est le fichier de conf : "config/packages/security.yaml", c'est vraiment le pivot du système d'authentification. Ce fichier comporte de nombreux commentaires explicatifs.



Contrairement au tuto que nous a montré Michel, il n'est PAS nécessaire d'exécuter la commande :
```bash
./bin/console make:user
```
Car nous avons déjà 2 entités d'utilisateurs : Staff et Member
Ils serviront de "providers" (d'ailleurs la commande "make:user" génère un provider dans le répertoire "Security" dans le cas où nous n'aurions pas de providers, mais verrons plus loin, avec le fichier "security.yaml" que nous affectons nous même ce provider via les entités Staff et Member)

À la place, il nous faut implémenter à la main l'interface UserInterface dans ces 2 classes d'entités
```php
class Staff implements UserInterface{
    //...
    // Cela nous fait rajouter les fonctions de cette interface :

    public function eraseCredentials(){}
    
    public function getSalt(){}

    // À noter que cette fonction existait déjà et retournait une collection de Role
    // car nous avons une relation ManyToMany entre les classes Staff et Role
    // On a donc rebaptisé la collection "roleCollection" au lieu de "roles" pour ne pas empiéter
    // le fonctionnement natif de l'interface UserInterface de Symfony
    public function getRoles(){}

    // Cette fonction existait déjà aussi
	public function getPassword(){}

}
```
Et pareil pour la classe Member, sauf que cette fois, il n'y a pas de relation en base entre Member et
Role, donc il n'y a pas eu de conflit avec "getRoles" de UserInterface
D'ailleurs, les Members se voient attribués un seul et même rôle constant, en tant qu'utilisateurs simples : ROLE_USER (qui est le role utilisateur de base de Symfony).

Il nous suffit maintenant de créer le formulaire de login, via la commande :
```bash
./bin/console make:auth

What style of authentication do you want? [Empty authenticator]:
  [0] Empty authenticator
  [1] Login form authenticator
 > 1

The class name of the authenticator to create (e.g. AppCustomAuthenticator):
 > StaffAuthenticator

Choose a name for the controller class (e.g. SecurityController) [SecurityController]:
 > SecurityStaffController

 Enter the User class that you want to authenticate (e.g. App\Entity\User) []:
 > App\Entity\Staff

 Which field on your App\Entity\Staff class will people enter when logging in? [username]:
  [0 ] id
  [1 ] first_name
  [2 ] last_name
  [3 ] username
  [4 ] password
  [5 ] email
  [6 ] address1
  [7 ] address2
  [8 ] zipcode
  [9 ] city
  [10] phone
  [11] roleCollection
 > 3

 Do you want to generate a '/logout' URL? (yes/no) [yes]:
 > yes

 created: src/Security/StaffAuthenticator.php
 updated: config/packages/security.yaml
 created: src/Controller/SecurityStaffController.php
 created: templates/security/login.html.twig

           
  Success! 
           

 Next:
 - Customize your new authenticator.
 - Finish the redirect "TODO" in the App\Security\StaffAuthenticatorAuthenticator::onAuthenticationSuccess() method.
 - Check the user's password in App\Security\StaffAuthenticator::checkCredentials().
 - Review & adapt the login template: templates/security/login.html.twig.

```

Symfony a créé 3 fichiers :

    1. "src/Security/StaffAuthenticator.php" qui est la classe étendue de AbstractFormLoginAuthenticator et qui est le service chargé de procéder à l'authentification.
    Comme l'indique le message de fin de Symfony en console, il faut notamment se charger d'écrire nous-même le code pour vérifier le password dans la fonction "checkCredentials"

    2. "src/Controller/SecurityStaffController.php" qui est donc le contrôleur contenant que 2 fonctions (routées) : login et logout

    3. "templates/security/login.html.twig" qui est donc la template

Première chose à faire : renommer le fichier template en "login.staff.html.twig" et donc, modifier le
contrôleur "SecurityStaffController::login" pour que la fonction "render" pointe sur ce nouveau fichier twig.

C'est nécessaire dans notre cas, car nous allons créer un 2è formulaire de login pour les Members,
mais la console Symfony "make:auth" tente toujours de créer le même template nommé "templates/security/login.html.twig" et plante s'il voit qu'un fichier de ce nom existe déjà.

Pour les détails sur les modifications apportées sur les différents fichiers, aller regarder simplement ces fichiers.

Nous allons ici expliquer dans les grandes lignes comment faire coexister ces 2 formulaires côte à côte.


Basiquement, comme les 2 formulaires ont un contrôleur, on va créer un autre contrôleur "LoginController" qui va inclure ces 2 formulaires dans sa template twig : "templates/login/index.html.twig"

Pour s'assurer que l'on tombe toujours sur la bonne route de la bonne page de login (non pas celle seulement de Staff ni seulement celle de Member, mais à chaque fois la page de LoginController avec les 2 formulaires), on modifie dans chacun des fichiers "src/Security/*Authenticator.php", la fonction "" :

```php
# fichier : src/Security/StaffAuthenticator.php (& pareil pour src/Security/MemberAuthenticator.php)
class StaffAuthenticator extends AbstractFormLoginAuthenticator
{
    // ... à la fin du fichier

    // Intialement
    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    // Remplacé par :
    protected function getLoginUrl()
    {
        // On met en dur, le route name de notre "LoginController::login"
        return $this->urlGenerator->generate('login_index');
    }
}

# fichier : src/Controller/LoginController
class LoginController extends AbstractController{
    /**
    * Le "route name", c'est bien "login_index"
    * @Route("/login", name="login_index")
    */
    public function index(){
        if ($this->getUser()){
            // Si on est déjà loggué, tenter d'accéder à la page de login renvoie à la dashboard
            return $this->redirectToRoute('dashboard');
        }
        return $this->render('login/index.html.twig');
    }
    //...
}
```

Dans "security.yaml", on doit spécifier 2 providers :
```yaml
security:
    providers:
        # used to reload user from session & other features (e.g. switch_user)
        app_member_provider:
            entity:
                class: App\Entity\Member
        app_staff_provider:
            entity:
                class: App\Entity\Staff

```

On définit donc pour tel nom (que l'on définit nous-même) de "provider", quelle est la classe d'entité utilisateur qui lui est associée (initialement, le nom du provider était "app_user_provider")


Là où le fichier "security.yaml" va s'avérer crucial, c'est dans sa gestion du "logout". Il faut préciser sur quelle "route" Symfony va exécuter ses fonctions natives de nettoyage de session et de redirection après déconnexion. Ça se passe dans la sous-section "logout" de la section "main" de "firewalls" :
```yaml
    firewalls:
        # (...)
        main:
            # (...)
            logout:
                path: logout # quand le navigateur fera une requête sur l'URL /logout (de la route nommée "logout"), Symfony interceptera avant même que le controleur ne s'exécute cette requête et fera le nettoyage de la session puis la redirection vers la page indiquée ci-dessous dans target
                target: login_index

            ## Cette sous-section logout peut s'implémenter dans un autre "firewall", par exemple, si on avait un espace admin spécifique :
        admin:
            logout:
                path: admin_logout #c'est un exemple. Dans notre projet, il n'existe pas de route name "admib_logout"
            targer: home_index
```

Pour finir, regarder simplement dans le fichier "security.yaml" comment sont hiérarchisés les roles et
comment les droits d'accès sont donnés en fonction des URLs.


## Traductions

En principe, le composant "translation" est déjà installé avec Symfony par défaut.

Pour amorcer les traductions, il suffit d'aller sur l'un des fichiers Twig, et là où il y a du texte (peu importe en anglais ou en français),
on met plutôt ces lignes :
```twig
<button class="btn">Delete</button>
<!-- Remplacé par : --!>
<button class="btn">{{ 'Delete'|trans }}</button>
```

Puis en console, on exécute :
```bash
# pour la locale "fr"
./bin/console translation:update --force fr
# pour la locale "fr_FR"
./bin/console translation:update --force fr_FR
# pour la locale "en_US"
./bin/console translation:update --force en_US
```

Cela génère automatiquement la création du répertoire "translations" à la racine du projet + les fichiers "xlf" de trads (type XML). Pour "fr", ces fichiers sont suffixés par "fr.xlf"

Il suffit alors de parcourir le fichier pour éditer tous les tags "target" correspondant à la langue cible :
```xml
<trans-unit id="4tClSWj" resname="Delete">
    <source>Delete</source>
    <target>Supprimer</target>
</trans-unit>
```

Et pour appliquer une langue par défaut sur l'ensemble du site, on peut modifier la valeur de "default_locale" dans le fichier "config/packages/translation.yaml" :
```yaml
framework:
    default_locale: fr
```

Il n'y a plus qu'à parcourir tous les fichiers Twig pour accoler la fonctions "trans" sur les chaînes de caractères à traduire.

NOTE : dans Twig, la fonction "trans" est une fonction implémentée (ou propre) aux objets de type "string" et donc, ça marche un peu comme en Javascript lorsqu'on utilise par exemple la fonction "String.toLowerCase()" où l'on peut avoir un code écrit comme suit :
```js
var strLowerCased = "any string".toLowerCase();
```

Donc, dans un Twig, si on tape :
```twig
<span>{{ trans('any string') }}</span>
```
cela provoquera une erreur disant que la fonction "trans" n'existe pas.

NOTE 2 : pour utiliser des chaînes à remplacer par une variable, comme je n'ai pas trouvé de doc vraiment explicite, on peut utiliser la fonction "replace" comme suit :
```twig
<span>{{ 'Hello %name%'|trans|replace({'%name%': app.user.username}) }}</span>
```

"replace" étant aussi une fonction propre de l'objet String dans Twig.
