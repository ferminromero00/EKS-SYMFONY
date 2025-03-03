#!/bin/bash
# Actualizar el sistema
sudo yum update -y

# Instalar Git
sudo dnf install -y git

cd /var
mkdir mywall
sudo git clone https://github.com/ferminromero00/EKS-SYMFONY.git

sudo mv EKS-SYMFONY/dockerfiles/Dockerfile_Mywall EKS-SYMFONY/MyWall

# Instalar Docker
sudo dnf install -y docker
sudo systemctl start docker
sudo systemctl enable docker

# Instalar kubectl
curl -LO "https://dl.k8s.io/release/v1.31.1/bin/linux/amd64/kubectl"
chmod +x ./kubectl
mkdir -p $HOME/bin && cp ./kubectl $HOME/bin/kubectl && export PATH=$PATH:$HOME/bin

# Instalar eksctl
curl -Lo eksctl_Linux_amd64.tar.gz https://github.com/weaveworks/eksctl/releases/latest/download/eksctl_Linux_amd64.tar.gz
tar -xzf eksctl_Linux_amd64.tar.gz -C /tmp && rm eksctl_Linux_amd64.tar.gz
sudo mv /tmp/eksctl /usr/local/bin

# Descargar la imagen de Mywall de DockerHub
# sudo docker pull ferminromero00/mywall

# sudo docker build -t mywall-php -f Dockerfile_Mywall .

# Ejecutar el contenedor con las variables de entorno correctas
# sudo docker run -d -p 80:80 -e APP_ENV=prod -e APP_DEBUG=0 --name mywall_container ferminromero00/mywall:latest
