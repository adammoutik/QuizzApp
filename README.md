# Système de Quiz en Laravel

## Description du Projet
Le système de quiz est une application de quiz à choix multiples simple conçue pour permettre aux enseignants de tester leurs étudiants et d'évaluer leurs performances. Ce système comprend plusieurs fonctionnalités pour rendre le processus de quiz efficace et pratique.

### Fonctionnalités

1. **Limite de temps du quiz**
    - Le quiz a une limite de temps definie par le professeur. Une fois ce délai écoulé, la session de l'étudiant pour l'examen expirera automatiquement.

2. **Une seule tentative**
    - Chaque étudiant peut tenter le quiz une seule fois pour maintenir l'équité et prévenir la tricherie.

4. **Examen et communication de l'administrateur**
    - L'administrateur reçoit une notification par e-mail lorsqu'un étudiant termine un quiz. L'administrateur peut ensuite renvoyer un e-mail à l'étudiant avec sa note et son résultat (Accepté ou Rejeté).

5. **Accès privé**
    - Le système de quiz est privé, et tous les utilisateurs, y compris les administrateurs, doivent se connecter pour y accéder.

6. **Fonctionnalités des utilisateurs**
    - Les utilisateurs réguliers (étudiants) peuvent :
        - Se connecter
        - Passer un test
        - Répondre à des questions à choix multiples
        - Examiner un rapport de leurs réponses, y compris les bonnes réponses et leur note.

7. **Fonctionnalités de l'administrateur**
    - Les administrateurs peuvent :
        - Créer des quiz avec des questions à choix multiples

### Questions du Quiz
- Les questions du quiz sont uniquement à choix multiples, chaque question ayant quatre choix et une seule réponse correcte.

## Utilisation
Pour configurer et utiliser le système de quiz, suivez ces étapes :

1. **Installation**
    - Clonez ce dépôt sur votre machine locale.
    - Installez les dépendances :
      ```bash
      composer install
      ```

2. **Configuration de la base de données, du courrier et de la file d'attente**
    - Configurez les paramètres de votre base de données dans le fichier `.env`.

3. **Création de l'administrateur**
    - Pour créer un administrateur, utilisez la commande suivante :
      ```bash
      php artisan create:admin
      ```

4. **Exécution de l'application**
    - Lancez le serveur de développement :
      ```bash
      php artisan serve
      ```
    - Démarrez le travailleur de file d'attente :
      ```bash
      php artisan queue:work
      ```
## Les nouveaux outils utilisé :

1. **Jobs :**
   - *Explication :* Les jobs sont utilisés pour exécuter des tâches en arrière-plan de manière asynchrone, souvent avec des files d'attente pour des opérations longues.

2. **Soft Delete :**
   - *Explication :* La suppression douce marque les enregistrements comme supprimés au lieu de les supprimer réellement, utile pour récupérer des données supprimées si nécessaire.

3. **Middleware Group :**
   - *Explication :* Les groupes de middleware regroupent des filtres pour les appliquer à des routes spécifiques, améliorant la modularité et la lisibilité du code.

4. **Création et utilisation de nouveaux middlewares :**
   - *Explication :* Les middlewares personnalisés ajoutent des étapes intermédiaires au traitement des requêtes, offrant une flexibilité dans la gestion des flux de requêtes HTTP.

