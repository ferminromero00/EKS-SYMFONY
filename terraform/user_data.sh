#!/bin/bash
# Actualizar el sistema
sudo yum update -y

# Instalar Git
sudo dnf install -y git

# Clonar el repositorio
cd /var
sudo git clone https://github.com/ferminromero00/EKS-SYMFONY.git

# Crear directorio y copiar archivos
sudo mv EKS-SYMFONY/dockerfiles/Dockerfile_Mywall EKS-SYMFONY/MyWall

# Instalar Docker
sudo dnf install -y docker
sudo systemctl start docker
sudo systemctl enable docker

# Construir la imagen
cd /var/EKS-SYMFONY/MyWall
# sudo docker build -t mywall_symfony -f Dockerfile_Mywall .

# Ejecutar el contenedor con variables de entorno
sudo docker run -d \
    -p 80:80 \
    -e APP_ENV=prod \
    -e APP_DEBUG=0 \
    --name mywall_container \
    mywall_symfony
