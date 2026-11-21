# YLCD-ASBL — Site web et espace d'administration

Site de la **Youth League for Community Development** (Goma, RDC), en PHP 8 (architecture MVC sans framework) et MySQL/MariaDB.

## Installation sous WAMP

1. Démarrer WampServer (icône verte).
2. Ouvrir **phpMyAdmin** → onglet *Importer* → choisir `database/ylcd_asbl.sql` → *Exécuter*.
   Le script crée la base `ylcd_asbl`, les tables et les données initiales. Il peut être relancé sans créer de doublons.
3. Ouvrir **http://localhost/ylcd_asbl/**
4. Cliquer sur **Se connecter** avec `admin` / `admin` : le site oblige alors à choisir un nouveau nom
   d'utilisateur, un email et un mot de passe (et éventuellement une photo).

### Mise à jour d'une base existante

Si la base a été importée **avant le passage au bilingue** (colonnes `titre`, `description`… sans `_fr`/`_en`),
ne réimportez pas `ylcd_asbl.sql` : importez **une seule fois** `database/migrations/2026_11_21_bilingue.sql`.
Les textes existants deviennent la version française (rien n'est perdu) et les traductions anglaises
du contenu d'origine sont ajoutées, sauf pour les textes déjà modifiés depuis l'admin.

### Base de données

Les paramètres se trouvent dans `app/Config/config.php` (section `db`) :

| WAMP utilise… | Port |
|---|---|
| MySQL | `3306` (valeur par défaut du projet) |
| MariaDB | `3307` → changer `'port' => 3307` |

Pour des réglages propres à une machine (mot de passe SMTP, base de production…), créer
`app/Config/config.local.php` qui retourne uniquement les clés à remplacer (ce fichier est ignoré par git) :

```php
<?php
return [
    'db'   => ['port' => 3307],
    'mail' => ['smtp' => ['enabled' => true, 'user' => 'ylcdrc21@gmail.com', 'pass' => 'mot-de-passe-application']],
];
```

### Envoi des emails (formulaire de contact)

Chaque message est **toujours enregistré** en base (menu *Messages* de l'admin) puis envoyé à `mail.to`.
Sous WAMP, la fonction `mail()` de PHP n'envoie rien : il faut activer le SMTP.
Avec Gmail : activer la validation en 2 étapes, créer un **mot de passe d'application**
(Compte Google → Sécurité), puis le mettre dans `mail.smtp.pass`.
La colonne *Email* de la liste des messages indique si la copie a bien été envoyée.

## Architecture (MVC)

```
index.php                 Contrôleur frontal : toutes les URL passent par ici
.htaccess                 Réécriture d'URL (/activites → index.php?url=activites), taille des uploads
app/
├── Config/
│   ├── config.php        Paramètres (base, email, uploads, chiffres du site)
│   └── routes.php        Table des routes URL → Contrôleur@action
├── Core/                 Le "mini-framework"
│   ├── bootstrap.php     Autoload, session sécurisée, gestion des erreurs
│   ├── Router.php        Routage + middlewares (guest, auth, admin) + vérification CSRF
│   ├── Controller.php    Rendu des vues dans un layout, retour formulaire avec erreurs
│   ├── Model.php         CRUD générique (PDO, requêtes préparées, colonnes autorisées)
│   ├── Database.php      Connexion PDO unique
│   ├── Auth.php          Utilisateur connecté, connexion / déconnexion
│   ├── Lang.php          Langue de l'interface (FR par défaut / EN), changement via ?lang=
│   ├── Validator.php     Règles de validation des formulaires (messages traduits, champs bilingues)
│   ├── Upload.php        Envoi sécurisé des fichiers (type réel vérifié, nom aléatoire)
│   ├── Mailer.php        Envoi d'email (SMTP ou mail())
│   └── helpers.php       e(), url(), __(), tr(), theme(), form_field(), query_url(), avatar(), date_activite()…
├── Lang/
│   └── en.php            Traductions anglaises de l'interface (clé = texte français)
├── Controllers/          HomeController, PageController, ContactController, AuthController, ProfileController
│   └── Admin/            Dashboard, Activite, Projet, Service, Membre, User, Message
├── Models/               User, Activite, ActiviteMedia, Service, Projet, Membre, Contact
└── Views/
    ├── layouts/          site.php (public), auth.php (connexion), admin.php (espace connecté)
    ├── partials/         en-tête, pied de page, bannière, formulaire de contact, champs image…
    ├── site/             accueil, à propos, services, activités, équipe
    ├── auth/             connexion, inscription
    ├── profile/          profil, configuration du compte par défaut
    ├── admin/            tableau de bord + listes et formulaires de chaque table
    └── errors/           404, 403
assets/
├── css/                  style.css (site public), admin.css (connexion + espace admin), Bootstrap, Owl Carousel
│                         (chacun se termine par son bloc « MODE SOMBRE »)
├── js/                   app.js (site public), admin.js (espace admin), theme.js (mode clair / sombre),
│                         jQuery, Bootstrap, Owl Carousel
└── img/                  Images fixes du design (voir « Images » ci-dessous)
uploads/                  Fichiers envoyés depuis l'admin : un dossier par table (activites, projets, services, membres, users)
database/
├── ylcd_asbl.sql         Schéma bilingue + données initiales (installation neuve)
└── migrations/           Mises à jour à appliquer sur une base existante
storage/logs/app.log      Journal des erreurs
```

Les dossiers `app/`, `database/` et `storage/` sont interdits d'accès depuis le navigateur ;
`uploads/` ne peut exécuter aucun script.

## Images

Les images sont rangées à deux endroits, selon qui les gère :

| Dossier | Contenu | Géré par |
|---|---|---|
| `assets/img/` | Images **fixes du design** : logo, bannières des pages, carrousel de l'accueil, page de connexion, fonds du CSS, image du footer | Le développeur (fichiers du projet) |
| `uploads/<table>/` | Images du **contenu** : médias des activités, images des projets et services, photos des membres et des comptes | L'admin, depuis l'espace d'administration |

Contenu de `assets/img/` :

| Fichier | Utilisé dans |
|---|---|
| `logo-ylcd.jpg` | Logo de la navbar, icône d'onglet (favicon), slider et section « Notre mission » de l'accueil, À propos, pages de connexion et admin, image par défaut d'une activité sans média |
| `brevets-anglais-2026-1.jpg` | Slide 1 de l'accueil, À propos, colonne d'image des pages de connexion, bannière par défaut |
| `environnement-2023-2.jpg` | Slide 2 de l'accueil, carrousel « Qui sommes-nous ? », bannière Activités |
| `anglais-1.jpg` | Slide 3 de l'accueil |
| `leadership-2.jpg` | Carrousel « Qui sommes-nous ? », bannière À propos |
| `informatique-2026-1.jpg` | Carrousel « Qui sommes-nous ? », bannière Services |
| `certificats-1.jpg` | Section « Contactez-nous » |
| `certificats-2.jpg` | Bannière Équipe |
| `presentation-anglais.jpg`, `environnement-2023-1.jpg`, `informatique-2026-2.jpg` | Section « L'équipe en action » (page Équipe) |
| `vbg-1.jpg` | Fond de la classe CSS `.equipe1` |
| `imgFooter.jpg` | Fond du pied de page |
| `favicon_ylcd_64x64.png` | Image de remplacement quand un élément n'a pas d'image |

Les images du design ne doivent pas pointer vers `uploads/` : si l'admin supprimait l'activité
correspondante, le fichier serait effacé et la bannière cassée. Certaines photos existent donc
volontairement à la fois dans `assets/img/` et dans `uploads/activites/`.

Pour changer une image du design, remplacez le fichier dans `assets/img/` en gardant le même nom,
ou mettez à jour sa référence dans la vue concernée (`app/Views/…`) ou dans `assets/css/style.css`.

## Base de données

Chaque champ **texte affiché sur le site** existe en deux colonnes, `<champ>_fr` et `<champ>_en`
(ex. `titre_fr` / `titre_en`). Les images, dates, ordres et noms propres n'ont qu'une colonne.

| Table | Champs bilingues (`_fr` / `_en`) | Autres champs | Fichiers |
|---|---|---|---|
| `users` | — | `username`, `email`, `photo` (optionnelle), `password` (hash), `role` (`admin`/`membre`), `must_change_credentials` | `uploads/users/` |
| `activites` | `titre`, `sous_titre`, `description` | `categorie`, `date_activite` (vide = activité régulière), `est_publie` | — |
| `activite_medias` | — | Images / vidéos d'une activité (plusieurs par activité) : `chemin`, `type`, `ordre` | `uploads/activites/` |
| `services` | `titre`, `pole`, `slogan`, `resume`, `description`, `points` | `slug` (ancre), `image`, `ordre` | `uploads/services/` |
| `projets` | `titre`, `categorie`, `description`, `objectif` | `image`, `service_id`, `ordre` | `uploads/projets/` |
| `membres` | `fonction`, `bio` | `nom` (nom propre), `photo`, `email`, `linkedin`, `est_coordinateur`, `ordre` | `uploads/membres/` |
| `contacts` | — (texte saisi par le visiteur) | `nom`, `email`, `telephone`, `sujet`, `message`, `est_lu`, `email_envoye` | — |

> La « confirmation du mot de passe » n'est **pas** une colonne : elle sert seulement à vérifier la saisie.
> Seul le hash du mot de passe (`password_hash`) est stocké. Les images ne sont jamais stockées en base, uniquement leur chemin.

## Bilingue français / anglais

- **Changer de langue** : sélecteur `FR | EN` dans la barre de navigation du site, la barre du haut de l'admin
  et les pages de connexion. Techniquement : n'importe quelle URL suivie de `?lang=en` ou `?lang=fr`.
  Le choix est mémorisé (session + cookie d'un an). Le français est la langue par défaut.
- **Textes de l'interface** (menus, boutons, messages, textes fixes des pages) : écrits en français dans le code
  avec `__('Se connecter')`, traduits dans `app/Lang/en.php`. Un texte absent du dictionnaire s'affiche en français.
  Paramètres : `__('Bienvenue, :name !', ['name' => $nom])`.
- **Contenu de la base** : affiché avec `tr($ligne, 'titre')`, qui lit `titre_fr` ou `titre_en` selon la langue.
  Si la traduction est vide, l'autre langue est affichée (jamais de texte vide).
- **Dans l'admin**, chaque formulaire a deux onglets *Français* / *English*. Les champs obligatoires
  (ex. le titre) doivent être remplis dans les deux langues ; un point rouge signale l'onglet qui contient une erreur.
  La colonne *Traduction* des listes indique les éléments dont la version anglaise manque.
- **Dates** : affichées dans la langue choisie (« Juillet 2026 » / « July 2026 »).
- **Ajouter un texte d'interface** : écrire `__('Mon texte')` dans la vue, puis ajouter
  `'Mon texte' => 'My text'` dans `app/Lang/en.php`.
- **Ajouter une langue** (ex. swahili) : ajouter `'sw' => 'Kiswahili'` dans `Lang::LOCALES`, créer `app/Lang/sw.php`,
  et ajouter les colonnes `_sw` aux tables (migration).

## Mode clair / sombre

- Bouton lune / soleil à côté du sélecteur de langue, sur le site, l'admin et les pages de connexion.
- Le mode **clair** (couleurs d'origine du site) est le mode par défaut.
- Le choix est mémorisé dans un cookie `theme` lu par PHP : la page s'affiche directement dans le bon thème, sans clignotement.
- Les couleurs sombres sont regroupées dans le bloc « MODE SOMBRE » à la fin de `style.css` et de `admin.css`
  (sélecteur `[data-theme="dark"]`) : le mode clair n'est pas modifié.

## Recherche et pagination

- Toutes les listes de l'admin (activités, projets, services, membres, utilisateurs, messages) et la page
  publique *Activités* affichent **10 éléments par page** avec *Précédent* / numéros de page / *Suivant*
  et l'indication « Affichage de 1 à 10 sur 27 ».
- Un champ de **recherche** (`?q=`) filtre sur les textes en français **et** en anglais ; il se combine avec
  le filtre par catégorie et la pagination (les paramètres sont conservés d'une page à l'autre).
- Nombre par page : constante `PER_PAGE` de `app/Core/Model.php`. Colonnes recherchées : `$searchable` de chaque modèle.

## Rôles et règles

- **Visiteur** : voit le site, envoie un message, peut **se connecter** ou **créer un compte**.
- **Membre** (rôle par défaut à l'inscription) : voit et modifie son profil (nom d'utilisateur, email, mot de passe, photo).
  Sans photo, l'avatar affiche ses initiales.
- **Admin** : gère activités, projets, services, membres de l'équipe, messages et comptes ; peut passer un membre en admin.
- Si la table `users` est vide, le **premier compte créé devient admin**.
- L'admin par défaut (`admin`/`admin`) doit changer ses identifiants à la première connexion : aucune autre page n'est accessible avant.
- Il reste toujours au moins un admin (impossible de rétrograder ou supprimer le dernier) ; un admin ne peut pas supprimer son propre compte.

## Sécurité

Mots de passe hachés, requêtes préparées (PDO), échappement HTML de toutes les sorties (`e()`), jeton CSRF sur
tous les formulaires, session régénérée à la connexion, limitation des essais de connexion,
type réel des fichiers vérifié à l'envoi, champ piège anti-robots sur le formulaire de contact.

## Faire évoluer le projet

Exemple : donner aux membres la possibilité de proposer des activités.

1. **Route** dans `app/Config/routes.php` (groupe `auth`) : `$r->get('mes-activites', 'MembreEspaceController@index');`
2. **Contrôleur** dans `app/Controllers/` qui utilise les modèles existants.
3. **Vue** dans `app/Views/…`, rendue avec `$this->render('…', [...], 'admin')`.
4. **Menu** : ajouter le lien dans `$__menu` de `app/Views/layouts/admin.php` pour le rôle concerné.

Pour une nouvelle table : la créer dans `database/`, puis un modèle de quelques lignes qui étend `App\Core\Model`
(`$table`, `$fillable`, `$orderBy`, `$searchable`) donne immédiatement `all()`, `find()`, `create()`, `update()`,
`delete()`, `count()`, `paginate()` et `searchClause()`. Pour un champ texte affiché sur le site, créer les deux
colonnes `<champ>_fr` / `<champ>_en`, utiliser `->bilingual()` dans la validation, `lang_tabs()` + `form_field()`
dans le formulaire, et `tr()` dans les vues.

Si `mod_rewrite` n'est pas disponible chez l'hébergeur, mettre `'pretty_urls' => false` : les liens deviennent
`index.php?url=activites`.
