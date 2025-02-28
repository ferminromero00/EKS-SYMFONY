resource "aws_security_group" "grupo_seguridad_servidor" {
  vpc_id      = aws_vpc.principal.id
  name        = "grupo-seguridad-servidor"
  description = "Grupo de seguridad para servidor web"

  # Regla de entrada para HTTP
  ingress {
    from_port   = 80
    to_port     = 80
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
    description = "Acceso HTTP desde cualquier origen"
  }
  # Regla de entrada para SSH
  ingress {
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
    description = "Acceso SSH desde cualquier origen"
  }
  ingress {
    from_port   = 3000
    to_port     = 3000
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
    description = "Acceso JSON-SERVER"
  }
  ingress {
    from_port   = 443
    to_port     = 443
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
    description = "Acceso HTTPS desde cualquier origen"
  }
  ingress {
    from_port   = 389
    to_port     = 389
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
    description = "Acceso LDAP desde cualquier origen"
  }
  # Regla de salida para todo el trafico
  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
    description = "Permite todo el trafico de salida"
  }

  tags = {
    Name = "GrupoSeguridadServidor"
  }
}

resource "aws_security_group" "efs_sg" {
  name        = "EKS-SG-EFS"
  description = "Grupo de seguridad para EFS en el cluster EKS"
  vpc_id      = aws_vpc.principal.id

  # Regla de entrada para NFS (EFS usa el puerto 2049)
  ingress {
    from_port   = 2049
    to_port     = 2049
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"] # ⚠️ Reemplaza con un rango de IPs seguro si es posible
    description = "Acceso NFS desde cualquier origen"
  }

  # Reglas de salida (permitiendo todo el tráfico)
  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
    description = "Permite todo el tráfico de salida"
  }

  tags = {
    Name = "EKS-SG-EFS"
  }
}
