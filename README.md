#  EcoRide – Plateforme de covoiturage éco-responsable

EcoRide est une plateforme web de covoiturage développée avec **Symfony**, permettant aux utilisateurs de proposer, réserver et gérer des trajets, tout en favorisant une mobilité plus écologique.

---

##  Fonctionnalités principales

###  Utilisateur
- Inscription & connexion sécurisées
- Vérification par email
- Réservation de trajets
- Gestion des crédits
- Annulation de réservation
- Validation du trajet après arrivée
- Dépôt d’un avis avec note
- Signalement d’un incident
- Réinitialisation du mot de passe sécurisée

---

###  Chauffeur
- Devenir chauffeur avec ajout de véhicule
- Création de trajets
- Consultation des réservations
- Démarrage et fin de trajet
- Génération des gains
- Consultation des avis reçus

---

###  Employé
- Validation ou refus des avis
- Suivi des incidents
- Gestion des trajets problématiques

---

###  Administrateur
- Création des comptes employés
- Suspension des comptes utilisateurs/employés
- Visualisation de graphiques :
  - Nombre de covoiturages par jour
  - Gains de la plateforme
- Total des crédits générés par la plateforme

---

##  Sécurité

- Connexion sécurisée Symfony
- Brute-force protégé
- Limite de demandes de reset de mot de passe
- Tokens expirables
- Validation email obligatoire
- Accès par rôles (USER / CHAUFFEUR / EMPLOYE / ADMIN)

---

## Technologies utilisées

- Symfony 6
- Doctrine ORM
- Twig
- MySQL
- Bootstrap 5
- Symfony Mailer
- Chart.js (graphiques admin)

---

##  Installation du projet

```bash
git clone https://github.com/ton-projet/ecoride.git
cd ecoride
composer install
npm install
npm run dev
