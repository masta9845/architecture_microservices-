# architecture_microservices-

# Gestion de Post-its - Architecture Microservices

Ce projet est une application de gestion de post-its basée sur une architecture **microservices**. L'application est conteneurisée avec **Docker**, orchestrée avec **Kubernetes**, et sécurisée avec **HTTPS** et des règles **RBAC**. Ce README fournit toutes les informations nécessaires pour configurer, déployer, et maintenir ce projet.


## 📋 **Prérequis**
Pour exécuter ce projet, vous devez installer les outils suivants :

### 1. **Technologies nécessaires en local**
- [Docker](https://www.docker.com/) : Pour la conteneurisation des services.
- [Kubernetes](https://kubernetes.io/) (Minikube recommandé) : Pour l'orchestration des services.
- [OpenSSL](https://www.openssl.org/) : Pour générer des certificats SSL.
- **Windows ou Linux** :
  - Pour **Windows** : Utilisez PowerShell ou WSL2 (Windows Subsystem for Linux).
  - Pour **Linux** : Terminal Bash.
- [kubectl](https://kubernetes.io/docs/tasks/tools/install-kubectl/) : Pour gérer les ressources Kubernetes.
- Navigateur Web : Pour accéder à l'application via `https://postit.local`.

---

## 🚀 **Installation et Utilisation**

### 1. **Création des images Docker**
Chaque service est conteneurisé avec Docker. Voici comment créer les images et les publier sur Docker Hub :

#### Étapes :
1. Clonez ce dépôt :
   ```bash
   git clone https://github.com/votre-utilisateur/postit-projet.git
   cd postit-projet
   ```

2. Connectez-vous à Docker :
   ```bash
   docker login
   ```

3. **Construisez les images Docker pour chaque service :**
   ```bash
   docker build -t votre-utilisateur-dockerhub/front-service ./front-service
   docker build -t votre-utilisateur-dockerhub/user-service ./user-service
   docker build -t votre-utilisateur-dockerhub/postit-service ./postit-service
   ```

4. **Poussez les images sur Docker Hub :**
   ```bash
   docker push votre-utilisateur-dockerhub/front-service
   docker push votre-utilisateur-dockerhub/user-service
   docker push votre-utilisateur-dockerhub/postit-service
   ```

### 2. **Mise à jour après une modification**
Si vous apportez des modifications (par exemple, dans le code ou les fichiers statiques), voici comment mettre à jour les images Docker et Kubernetes :

1. **Reconstruisez l'image du service modifié :**
   ```bash
   docker build -t votre-utilisateur-dockerhub/front-service ./front-service
   ```

2. **Poussez l'image mise à jour sur Docker Hub :**
   ```bash
   docker push votre-utilisateur-dockerhub/front-service
   ```

3. **Redémarrez le déploiement Kubernetes pour utiliser la nouvelle image :**
   ```bash
   kubectl set image deployment/front-service front-service=utilisateur-dockerhub/front-service:latest -n postit-projet
   ```

---

### 3. **Configuration et déploiement avec Kubernetes**
Le projet utilise **Minikube** pour l'orchestration des conteneurs.

#### Étapes pour déployer le projet :
1. Démarrez Minikube :
   ```bash
   minikube start
   ```

2. Créez le namespace Kubernetes :
   ```bash
   kubectl create namespace postit-projet
   ```

3. Créez les ConfigMaps et TLS Secrets nécessaires :
   ```bash
   kubectl create configmap init-sql-config --from-file=init.sql -n postit-projet
   kubectl create secret tls postit-tls --key certs/tls.key --cert certs/tls.crt -n postit-projet
   ```

4. Appliquez les fichiers de déploiement Kubernetes :
   ```bash
   kubectl apply -f kubernetes/
   ```

5. Configurez l’Ingress pour accéder à l’application via `https://postit.local`.

---

### 4. **Sécurisation et accès HTTPS**
Le projet utilise un certificat SSL pour sécuriser les communications.

#### Étapes pour la sécurisation :
1. **Génération du certificat SSL avec OpenSSL :**
   ```bash
   openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout tls.key -out tls.crt -subj "/CN=postit.local"
   ```

2. Placez les fichiers `tls.key` et `tls.crt` dans le dossier `certs/`.

3. Créez un secret Kubernetes pour le certificat :
   ```bash
   kubectl create secret tls postit-tls --key certs/tls.key --cert certs/tls.crt -n postit-projet
   ```

4. Configurez votre fichier `hosts` :
   - **Windows** :
     - Ouvrez `C:\Windows\System32\drivers\etc\hosts` avec un éditeur de texte en mode administrateur.
     - Ajoutez la ligne suivante :
       ```
       127.0.0.1 postit.local
       ```
   - **Linux** :
     - Modifiez le fichier `/etc/hosts` avec un éditeur comme `nano` :
       ```bash
       sudo nano /etc/hosts
       ```
     - Ajoutez la ligne suivante :
       ```
       127.0.0.1 postit.local
       ```

5. Accédez à l’application via [https://postit.local](https://postit.local). Si un avertissement de certificat non sécurisé apparaît, acceptez l’exception de sécurité.

---

### 5. **Gestion des permissions avec RBAC**
Pour restreindre les permissions Kubernetes, des règles **RBAC** ont été configurées.

#### Étapes :
1. **Vérifiez les rôles et bindings :**
   ```bash
   kubectl get roles -n postit-projet
   kubectl get rolebindings -n postit-projet
   ```

2. Les règles RBAC permettent uniquement les actions nécessaires (lecture/écriture sur les pods).

---

### 6. **Que faire après une modification majeure ?**
Si une modification majeure (comme un changement de fichier ou de certificat) est effectuée, suivez ces étapes pour redéployer correctement le projet :

1. Supprimez le namespace Kubernetes existant :
   ```bash
   kubectl delete namespace postit-projet
   ```

2. Redéployez le projet en suivant les étapes mentionnées dans la section Kubernetes.

---
## ⚙️ **Pipeline CI/CD**

Ce projet utilise GitHub Actions pour automatiser les processus de construction et de déploiement.

### Description du Pipeline
- **Construction des images Docker :** Les images des services `front-service`, `user-service`, et `postit-service` sont créées et publiées sur Docker Hub.
- **Déploiement sur Kubernetes :** Les nouvelles images sont déployées sur le cluster Kubernetes.

### Configuration des Secrets
Pour utiliser ce pipeline, vous devez configurer les secrets suivants dans votre dépôt GitHub :
- **DOCKER_USERNAME :** Votre nom d'utilisateur Docker Hub.
- **DOCKER_PASSWORD :** Votre token personnel Docker Hub.
- **KUBECONFIG :** (Facultatif) Le fichier de configuration pour accéder à votre cluster Kubernetes, si vous utilisez un cluster externe.

### Fichier Workflow
Le fichier `.github/workflows/cicd.yml` contient la configuration complète du pipeline CI/CD.

### Déclenchement du Pipeline
Le pipeline se déclenche automatiquement à chaque :
- Push sur la branche `main`.
- Pull request ouverte vers `main`.


---

## 📄 **Structure des Répertoires**

```
├── front-service/         # Service frontend
├── user-service/          # Service utilisateur
├── postit-service/        # Service de gestion des post-its
├── kubernetes/            # Manifests Kubernetes
├── .github/workflows/     # Configuration CI/CD
└── README.md              # Documentation
```
---
## 💡 **FAQ**

1. **Que faire si l’application ne s’affiche pas correctement ?**
   - Vérifiez les logs des pods :
     ```bash
     kubectl logs <pod-name> -n postit-projet
     ```

2. **Comment vérifier si les images Docker sont à jour ?**
   - Consultez Docker Hub ou utilisez la commande suivante :
     ```bash
     docker images
     ```

3. **Que faire si HTTPS ne fonctionne pas ?**
   - Assurez-vous que le certificat SSL est correctement configuré.
   - Vérifiez votre fichier `hosts` et le secret Kubernetes lié au certificat.


---

## 📄 **Licence**
Ce projet est sous licence MIT. Vous êtes libre de l'utiliser et de le modifier.

--- 

**Avec ce README, tout utilisateur ou développeur peut facilement comprendre, utiliser, et maintenir le projet.** 😊