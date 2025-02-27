<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241129100345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE articulo (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, precio DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cliente (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, administrador TINYINT(1) NOT NULL, nombre_apellidos VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE linea_pedido (id INT AUTO_INCREMENT NOT NULL, pedido_id INT NOT NULL, articulo_id INT NOT NULL, cantidad INT NOT NULL, INDEX IDX_183C31654854653A (pedido_id), INDEX IDX_183C31652DBC2FC9 (articulo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedido (id INT AUTO_INCREMENT NOT NULL, cliente_id INT DEFAULT NULL, fecha DATE NOT NULL, INDEX IDX_C4EC16CEDE734E51 (cliente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE linea_pedido ADD CONSTRAINT FK_183C31654854653A FOREIGN KEY (pedido_id) REFERENCES pedido (id)');
        $this->addSql('ALTER TABLE linea_pedido ADD CONSTRAINT FK_183C31652DBC2FC9 FOREIGN KEY (articulo_id) REFERENCES articulo (id)');
        $this->addSql('ALTER TABLE pedido ADD CONSTRAINT FK_C4EC16CEDE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE linea_pedido DROP FOREIGN KEY FK_183C31654854653A');
        $this->addSql('ALTER TABLE linea_pedido DROP FOREIGN KEY FK_183C31652DBC2FC9');
        $this->addSql('ALTER TABLE pedido DROP FOREIGN KEY FK_C4EC16CEDE734E51');
        $this->addSql('DROP TABLE articulo');
        $this->addSql('DROP TABLE cliente');
        $this->addSql('DROP TABLE linea_pedido');
        $this->addSql('DROP TABLE pedido');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
