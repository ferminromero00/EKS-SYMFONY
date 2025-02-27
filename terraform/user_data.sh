#!/bin/bash
# Actualizar el sistema
sudo yum update -y

# Instalar Git
sudo dnf install -y git

# Clonar el repositorio
cd /var
sudo git clone https://github.com/ferminromero00/EKS-SYMFONY.git

# Crear directorio y mover archivos
mkdir -p mywall
sudo mv EKS-SYMFONY/dockerfiles/Dockerfile_Mywall mywall/
sudo mv EKS-SYMFONY/Mywall mywall/

# Instalar Docker
sudo dnf install -y docker
sudo systemctl start docker
sudo systemctl enable docker

# Construir la imagen
cd /var/mywall
sudo docker build -t mywall_symfony -f Dockerfile_Mywall .

# Ejecutar el contenedor en segundo plano
sudo docker run -d -p 80:80 --name mywall_container mywall_symfony
