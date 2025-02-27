<?php
// src/Controller/PublicacionController.php

namespace App\Controller;

use App\Entity\Publicacion;
use App\Entity\Comentario;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublicacionesController extends AbstractController
{
    #[Route('/publicaciones/{fecha}', name: 'app_publicaciones_por_fecha', methods: ['GET'])]
    public function obtenerPublicacionesPorFecha(EntityManagerInterface $em, string $fecha): JsonResponse
    {
        // Convertir la fecha recibida en un objeto DateTime
        $fechaFormateada = \DateTime::createFromFormat('d-m-Y', $fecha);
        if (!$fechaFormateada) {
            return new JsonResponse(['error' => 'Formato de fecha inválido'], 400);
        }

        // Filtrar las publicaciones que tengan la fecha de creación igual a la proporcionada (sin tomar en cuenta la hora)
        $publicaciones = $em->getRepository(Publicacion::class)->findAll();
        $publicacionesFiltradas = [];

        foreach ($publicaciones as $publicacion) {
            // Compara solo la fecha (ignorando la hora)
            if ($publicacion->getFechaCreacion()->format('Y-m-d') === $fechaFormateada->format('Y-m-d')) {
                // Crear el array con la fecha de creación y el nombre del usuario
                $publicacionJson = [
                    'fechaCreacion' => $publicacion->getFechaCreacion()->format('Y-m-d H:i:s'),
                    'usuario' => $publicacion->getUsuario()->getUsername(),
                    'contenido' => $publicacion->getContenido(),
                ];

                // Añadir la publicación filtrada
                $publicacionesFiltradas[] = $publicacionJson;
            }
        }
        // Devolvemos la respuesta JSON con las publicaciones filtradas
        return $this->json($publicacionesFiltradas);
    }
}
