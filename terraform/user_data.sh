#!/bin/bash
# Actualizar el sistema
sudo yum update -y

# Instalar Git
sudo dnf install -y git

# Instalar Docker
sudo dnf install -y docker
sudo systemctl start docker
sudo systemctl enable docker

# Descargar la imagen de Mywall de DockerHub
sudo docker pull ferminromero00/mywall-symfony

# Ejecutar el contenedor con las variables de entorno correctas
sudo docker run -d -p 80:80 -e APP_ENV=prod -e APP_DEBUG=0 --name mywall_container ferminromero00/mywall-symfony:latest
