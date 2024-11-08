
# Miniboutique - Site de Vente de Matériel Gaming

Miniboutique est une boutique en ligne dédiée à la vente de matériel gaming, incluant des ordinateurs, écrans, claviers, souris, et autres équipements PC gamer. Ce projet a été développé pour apprendre et démontrer des compétences en HTML, CSS, JavaScript, PHP, et SQL.

## Fonctionnalités

- **Catalogue de produits** : Liste de divers équipements gaming.  
- **Gestion utilisateur** : Système de compte administrateur pour tester les opérations CRUD (Create, Read, Update, Delete).  
- **CRUD complet** : Gestion des produits via un compte administrateur.  
- **Système d'encaissement et mot de passe oublié défaillants :** Je n'ai malheureusement pas eu le temps de le corriger.  
- **Barre de recherche qui laisse à désirer** : Problèmes d'efficacité dans la recherche.  
- **Un seul Code promo disponible** : Celui-ci est : PROMO10 ou promo10. 


## Technologies Utilisées

- **Frontend** : HTML, CSS, JavaScript
- **Backend** : PHP, SQL
- **Serveur Local** : WampServer pour l'hébergement local et la gestion de la base de données

## Contexte du Projet

J'avais déjà des connaissances en HTML, CSS et un peu de JavaScript avant ce projet. Cependant, c'était la première fois que j'utilisais PHP et SQL, alors j'ai consulté l'IA et divers tutoriels en ligne pour m'aider à comprendre et mettre en œuvre ces technologies.

## Installation

1. **Téléchargez et installez [WampServer](https://www.wampserver.com/)** si ce n'est pas déjà fait.

2. **Clonez le dépôt** ou téléchargez les fichiers de la boutique sur votre machine.

3. Placez tous les fichiers du projet dans un dossier au sein de l'emplacement Wamp :
   - Ouvrez le dossier `wamp64/www` sur votre machine.
   - Créez un nouveau dossier nommé, par exemple, `miniboutique`, et copiez tous les fichiers du projet dans ce dossier.

4. **Installation de la base de données** :
   - Lancez WampServer et assurez-vous que le serveur est en ligne (icône verte).
   - Dans votre navigateur, allez à `http://localhost/miniboutique/install.php`.
   - Cette étape créera la base de données et les tables nécessaires.

5. **Accédez à la boutique** :
   - Après avoir installé la base de données, rendez-vous sur `http://localhost/miniboutique/index.php` pour accéder à la page d'accueil.

## Compte Administrateur pour Tester le CRUD

Pour tester les fonctionnalités de gestion des produits (CRUD), utilisez le compte administrateur de test :

- **Nom d'utilisateur** : `admintest`
- **Mot de passe** : `admintest`

Cela vous permettra d'ajouter, de modifier et de supprimer des produits ou utilisateurs dans la boutique.

## Remerciements

Un grand merci aux ressources en ligne et à l'IA qui m'ont guidé dans l'apprentissage(partiel voire preque nul) de PHP et SQL.

## Avertissement

Ce projet est conçu pour un usage éducatif. Le système d'authentification et de sécurité n'est pas optimisé pour un environnement de production.
