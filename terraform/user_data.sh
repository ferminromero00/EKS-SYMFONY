#!/bin/bash
# Actualizar el sistema
sudo yum update -y

# Instalar Git
sudo dnf install -y git

# Clonar el repositorio
cd /var
sudo git clone https://github.com/ferminromero00/EKS-SYMFONY.git

# Crear directorio y mover archivos
sudo mkdir -p /var/mywall
sudo cp -r EKS-SYMFONY/dockerfiles/Dockerfile_Mywall /var/mywall/
sudo cp -r EKS-SYMFONY/Mywall/* /var/mywall/

# Instalar Docker
sudo dnf install -y docker
sudo systemctl start docker
sudo systemctl enable docker

# Construir la imagen
cd /var/mywall
sudo docker build -t mywall_symfony -f Dockerfile_Mywall .

# Ejecutar el contenedor en segundo plano
sudo docker run -d -p 80:80 --name mywall_container mywall_symfony