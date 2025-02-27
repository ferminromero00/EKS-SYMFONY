#!/bin/bash
# Actualizar el sistema
sudo yum update -y

# Clonar para conseguir el dockerfile
sudo dnf install -y git

cd /var
mkdir mywall
sudo git clone https://github.com/ferminromero00/EKS-SYMFONY.git

# Mover archivos necesarios
sudo mv EKS-SYMFONY/dockerfiles/Dockerfile_Mywall mywall

# Instalar docker
sudo dnf install docker -y
sudo systemctl start docker

# Contruimos imagen
cd /var/mywall
sudo docker build -t mywall_symfony .

# Ejecutamos la imagen
sudo docker run -d -p 80:80 mywall-symfony