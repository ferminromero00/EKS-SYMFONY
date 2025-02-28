#!/bin/bash
# Actualizar el sistema
sudo yum update -y

# Instalar Git
sudo dnf install -y git

# Clonar el repositorio
#cd /var
# sudo git clone https://github.com/ferminromero00/EKS-SYMFONY.git

# Crear directorio y copiar archivos
# sudo mv EKS-SYMFONY/dockerfiles/Dockerfile_Mywall EKS-SYMFONY/MyWall

# Instalar Docker
sudo dnf install -y docker
sudo systemctl start docker
sudo systemctl enable docker

# Descargar el Mywall de Gerardo
sudo docker pull ferminromero00/mywall-symfony

