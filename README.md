Développement  et  déploiement  d’une  application  web  pour  la gestion du point de vente (PDV) des boutiques

1 
 
Armée du Salut 
UNIVERSITE WILLIAM BOOTH 
FACULTE DES SCIENCES INFORMATIQUES ET INTELLIGENCE ARTIFICIELLE 
KINSHASA/GOMBE 
BACHELOR 2  
CAHIER DES CHARGES FONCTIONNEL DE L’EXAMEN DE 
PROJET D’ATELIER DEVELOPPEMENT WEB FULL STACK  
Sujet  7 :  Développement  et  déploiement  d’une  application  web  pour  la 
gestion du point de vente (PDV) des boutiques ...... 
 
Objectif général : Créer une application web full stack (multiplateforme, sécurisée et 
responsive) pour permettre aux boutiques ....... de gérer efficacement leurs ventes, leur stock, 
leurs clients, leurs finances et leurs employés — en temps réel et à distance si nécessaire. 
 
Types d’utilisateurs 
1. Caissier/Vendeur 
2. Gestionnaire de stock 
3. Gérant / Responsable boutique 
4. Administrateur système 
5. Client (optionnel en phase avancée) 
 
Fonctionnalités détaillées par type d’utilisateur 
 
1. CAISSIER / VENDEUR 
Gestion des ventes 
• Interface simple et rapide de prise de commande 
• Scan ou sélection manuelle des produits 
• Application automatique des promotions 
• Affichage du total, TVA, remise, paiement 
• Modes de paiement : espèces, mobile money, carte 
• Génération automatique du ticket de caisse (PDF) 
Recherche produit 
• Par nom, référence, catégorie ou code-barres 
• Affichage instantané du stock disponible 
• Proposition de produits similaires ou alternatifs 
Journal de caisse 
• Décompte automatique en fin de journée 
• Historique des ventes personnelles 
• Rapport journalier de caisse imprimable 
 
2. GESTIONNAIRE DE STOCK 
Gestion de l’inventaire 
• Ajout, modification et suppression de produits 
• Suivi des stocks par produit et par boutique 
• Notifications automatiques en cas de stock faible 
• Historique des mouvements de stock 
Réceptions et livraisons 
• Enregistrement des livraisons fournisseurs 
• Transfert entre boutiques (multi-boutique) 
2 
 
 
• Suivi des commandes internes 
Gestion des produits 
• Catégories, marques, tailles, couleurs, références 
• Création de variantes (ex : robe bleue, taille M) 
• Impression d’étiquettes et code-barres 
 
3. GÉRANT / RESPONSABLE BOUTIQUE 
Suivi des performances 
• Tableau de bord des ventes (CA, top produits, panier moyen) 
• Rapport par vendeur / caissier 
• Classement des produits les plus vendus 
Gestion du personnel 
• Création et gestion des comptes vendeurs/caissiers 
• Suivi des performances par employé 
• Attribution de rôles et permissions 
Gestion financière 
• Suivi des encaissements et des paiements 
• Rapport journalier, hebdomadaire, mensuel 
• Export comptable (Excel, CSV, PDF) 
• Suivi des paiements différés (clients à crédit) 
 
4. ADMINISTRATEUR SYSTÈME 
Gestion technique 
• Gestion des utilisateurs, rôles et autorisations 
• Paramétrage global du système (TVA, langues, devises) 
• Configuration des points de vente (boutiques, terminaux) 
• Sauvegardes automatiques de la base de données 
• Journalisation des actions (logs) 
Maintenance système 
• Surveillance du système (pannes, connexions, erreurs) 
• Mises à jour de l’application 
• Gestion des intégrations API (paiement, facturation) 
 
5. CLIENT (optionnel, via interface publique ou mobile) 
Historique des achats 
• Visualisation des achats passés (ticket de caisse) 
• Téléchargement des factures 
• Suivi des commandes passées (en ligne ou réservées) 
Réclamation & fidélité 
• Système de fidélité (points cumulés, remises) 
• Formulaire de réclamation 
• Notification des nouvelles offres ou promotions 
 
Fonctionnalités transversales (tous profils) 
Fonctionnalité Détail 
Authentification sécurisée Connexion avec rôles (caissier, admin, etc.), 2FA 
Responsive & mobile-friendly Optimisé pour tablette/caissier mobile 
Multi-boutique Gestion de plusieurs points de vente 
Facturation Génération de facture normalisée 
Notifications Alertes de rupture de stock, fin de promo, anomalies 
Statistiques temps réel Graphiques interactifs, filtres par période 
 
 
 
3 
 
 
Résumé des modules selon profil utilisateur 
Module Caissier Gestionnaire Gérant Admin Client 
Vente & facturation               
Gestion de stock              
Tableau de bord               
Suivi des paiements               
Messagerie / alerte                
Statistiques et export              
 