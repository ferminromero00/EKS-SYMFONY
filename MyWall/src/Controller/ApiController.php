<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Publicacion; 

class ApiController extends AbstractController
{
    #[Route('/api/publicaciones', name: 'app_api_jsonpublicaciones', methods: ['GET', 'POST'])]
    public function publicaciones(EntityManagerInterface $em, Request $request, Security $security): JsonResponse
    {
        // Extraemos el usuario a partir del token JWT
        $usuario = $security->getUser();

        // Obtenemos todas las publicaciones del usuario
        $publicaciones = $em->getRepository(Publicacion::class)->findBy(['usuario' => $usuario->getId()]);

        // Mapeamos las publicaciones a un formato simple para devolver en JSON
        $datos = array_map(function($publicacion) {
            return [
                'id' => $publicacion->getId(),
                'contenido' => $publicacion->getContenido(),
                'imagen' => $publicacion->getImagen(),
                'fechaCreacion' => $publicacion->getFechaCreacion()->format('Y-m-d H:i:s'), // Formato de fecha
            ];
        }, $publicaciones);

        // Devolvemos los datos de las publicaciones en formato JSON
        return new JsonResponse($datos);
    }
}
