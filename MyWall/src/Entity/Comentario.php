<?php

namespace App\Entity;

use App\Repository\ComentarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComentarioRepository::class)]
class Comentario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'comentarios')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Usuario $usuario = null;

    #[ORM\ManyToOne(inversedBy: 'comentarios')]
    private ?Publicacion $publicacion = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'comentarios')]
    private ?self $comentarioPadre = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'comentarioPadre')]
    private Collection $comentarios;

    #[ORM\Column(length: 255)]
    private ?string $contenido = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeInterface $fechaCreacion = null;

    public function __construct()
    {
        $this->comentarios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getPublicacion(): ?Publicacion
    {
        return $this->publicacion;
    }

    public function setPublicacion(?Publicacion $publicacion): static
    {
        $this->publicacion = $publicacion;

        return $this;
    }

    public function getComentarioPadre(): ?self
    {
        return $this->comentarioPadre;
    }

    public function setComentarioPadre(?self $comentarioPadre): static
    {
        $this->comentarioPadre = $comentarioPadre;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getComentarios(): Collection
    {
        return $this->comentarios;
    }

    public function addComentario(self $comentario): static
    {
        if (!$this->comentarios->contains($comentario)) {
            $this->comentarios->add($comentario);
            $comentario->setComentarioPadre($this);
        }

        return $this;
    }

    public function removeComentario(self $comentario): static
    {
        if ($this->comentarios->removeElement($comentario)) {
            // set the owning side to null (unless already changed)
            if ($comentario->getComentarioPadre() === $this) {
                $comentario->setComentarioPadre(null);
            }
        }

        return $this;
    }

    public function getContenido(): ?string
    {
        return $this->contenido;
    }

    public function setContenido(string $contenido): static
    {
        $this->contenido = $contenido;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion(\DateTimeInterface $fechaCreacion): static
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }
}
