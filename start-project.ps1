Write-Output "Starting Minikube..."
minikube start

Write-Output "Creating namespace postit-projet..."
kubectl create namespace postit-projet

Write-Output "Creating ConfigMap for init.sql..."
kubectl create configmap init-sql-config --from-file=init.sql -n postit-projet

Write-Output "Creating TLS Secret..."
kubectl create secret tls postit-tls --key certs/tls.key --cert certs/tls.crt -n postit-projet

Write-Output "Applying RBAC configuration..."
kubectl apply -f kubernetes/role.yaml
kubectl apply -f kubernetes/rolebinding.yaml

Write-Output "Applying Kubernetes manifests..."
kubectl apply -f kubernetes/mysql-deployment.yaml
kubectl apply -f kubernetes/user-deployment.yaml
kubectl apply -f kubernetes/postit-deployment.yaml
kubectl apply -f kubernetes/front-deployment.yaml
kubectl apply -f kubernetes/ingress.yaml

Write-Output "Checking Kubernetes resources..."
kubectl get all -n postit-projet

Write-Output "Final resource check..."
kubectl get pods -n postit-projet
kubectl get services -n postit-projet
kubectl describe ingress postit-ingress -n postit-projet

Write-Output "Setup complete! Access the application at https://postit.local"
Start-Process powershell -ArgumentList "minikube tunnel"
