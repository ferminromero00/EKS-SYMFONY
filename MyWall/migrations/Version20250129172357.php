<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250129172357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comentario (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, publicacion_id INT DEFAULT NULL, comentario_padre_id INT DEFAULT NULL, contenido VARCHAR(255) NOT NULL, fecha_creacion DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4B91E702DB38439E (usuario_id), INDEX IDX_4B91E7029ACBB5E7 (publicacion_id), INDEX IDX_4B91E702C43F2AE1 (comentario_padre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publicacion (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, contenido VARCHAR(255) NOT NULL, imagen VARCHAR(255) DEFAULT NULL, fecha_creacion DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_62F2085FDB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E702DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E7029ACBB5E7 FOREIGN KEY (publicacion_id) REFERENCES publicacion (id)');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E702C43F2AE1 FOREIGN KEY (comentario_padre_id) REFERENCES comentario (id)');
        $this->addSql('ALTER TABLE publicacion ADD CONSTRAINT FK_62F2085FDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E702DB38439E');
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E7029ACBB5E7');
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E702C43F2AE1');
        $this->addSql('ALTER TABLE publicacion DROP FOREIGN KEY FK_62F2085FDB38439E');
        $this->addSql('DROP TABLE comentario');
        $this->addSql('DROP TABLE publicacion');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
