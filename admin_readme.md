# Configuration de l'Interface d'Administration Discover DZ

Ce document explique comment configurer et utiliser l'interface d'administration de Discover DZ.

## Configuration initiale

1. **Créer la table des administrateurs**
   - Exécutez le script SQL `admin_setup.sql` dans votre base de données MySQL
   - Ce script crée la table `admins` et ajoute un administrateur par défaut

2. **Identifiants par défaut**
   - Nom d'utilisateur: `admin`
   - Mot de passe: `admin123`
   - **Important**: Changez ce mot de passe après la première connexion!

## Fonctionnalités de l'administration

L'interface d'administration vous permet de:

- Consulter les statistiques générales du site (tableau de bord)
- Gérer les utilisateurs
- Approuver ou rejeter les expériences soumises par les utilisateurs
- Approuver ou rejeter les établissements ajoutés

## Sécurité

Pour renforcer la sécurité de votre interface d'administration:

1. Changez le mot de passe administrateur par défaut
2. Limitez l'accès à l'interface d'administration à des adresses IP spécifiques si possible
3. Envisagez d'implémenter une authentification à deux facteurs

## Personnalisation

Vous pouvez personnaliser l'apparence de l'interface d'administration en modifiant le fichier `styleadmin.css`.

---

© Discover DZ - Tous droits réservés