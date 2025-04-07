Page d'accueil
![alt text](https://github.com/SebastienHerbreteau/pixishare/blob/master/screenshot.jpg?raw=true)

Page des albums
![alt text](https://github.com/SebastienHerbreteau/pixishare/blob/master/screenshot2.jpg?raw=true)

Page d'un album
![alt text](https://github.com/SebastienHerbreteau/pixishare/blob/master/screenshot3.jpg?raw=true)

Modal de traitement/avancement
![alt text](https://github.com/SebastienHerbreteau/pixishare/blob/master/screenshot4.jpg?raw=true)

Modal photo
![alt text](https://github.com/SebastienHerbreteau/pixishare/blob/master/screenshot5.jpg?raw=true)

# PixiShare

Galerie photo personnelle.

J'ai créé ce projet car je me suis toujours interrogé sur l'utilisation des photos personnelles mises en ligne. Avec les nombreux cas de fuites de données, l'exploitation par les intelligences artificielles et d'autres risques, il est essentiel de garder le contrôle sur ses contenus personnels. Ce projet a donc pour but d'être auto-hébergé, offrant ainsi une solution privée et sécurisée.

PixiShare permet de visualiser ses photos de manière bien plus agréable que l'explorateur Windows. À terme, il offrira également la possibilité :
- de partager des albums  de façon éphémère avec des amis et/ou des membres de la famille.
- de modifier des images (à définir).

> **⚠️ Attention**  
> Ce projet n'a pas pour vocation de devenir l'unique sauvegarde de vos images. Il est indispensable de conserver une copie des fichiers originaux sur un disque dur externe.

---

## Fonctionnalités actuelles

- Upload de photos. (Jpeg, png, bmp)
- Script Python pour la gestion des images (redimensionnement, compression en webp).
- Hub Mercure pour le traitement asynchrone des images
- Affichage des galeries justifiées, façon Flickr. (https://miromannino.github.io/Justified-Gallery/)

---

## Fonctionnalités en cours de développement



---

## Prérequis

- PHP 8.4
- Python 3.13
- Node.js (pour les dépendances frontend)
- Docker

---

## Installation

1. **Cloner le dépôt** :  
   ```bash
   git clone https://github.com/SebastienHerbreteau/pixishare.git
   cd pixishare
   ```

2. **Installer les dépendances PHP** :  
   ```bash
   composer install
   ```

3. **Configurer le fichier d'environnement** :
      ```bash
   Installer une base de donnée de votre choix et configurer le fichier .env
   ```


5. **Lancer les migrations pour la base de données** :  
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

6. **Installer les dépendances JavaScript** :  
   Si tu utilises **npm** :
   ```bash
   npm install
   ```
   Si tu utilises **yarn** (privilégier un seul gestionnaire) :
   ```bash
   yarn install
   ```

7. **Installer les dépendances Python nécessaires pour le traitement des images** :  
   ```bash
   pip install imageio numpy pillow
   ```

8. **Compiler les assets frontend (si nécessaire)** :  
   Pour le mode développement :  
   ```bash
   npm run dev
   ```
   Pour le mode production :  
   ```bash
   npm run build
   ```

9. **Lancer le container Docker pour le hub Mercure ** :  
 
   ```bash
    docker-composer up --build
   ```



   
