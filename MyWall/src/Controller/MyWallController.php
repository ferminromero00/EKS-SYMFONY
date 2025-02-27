<?php

namespace App\Controller;

use App\Entity\Comentario;
use App\Entity\Publicacion;
use App\Form\PublicacionType;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyWallController extends AbstractController
{
    #[Route('/muro', name: 'app_muro')]
    public function muro(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publicacion = new Publicacion();
        $form = $this->createForm(PublicacionType::class, $publicacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publicacion->setUsuario($this->getUser());
            $publicacion->setFechaCreacion(new \DateTimeImmutable());
            $entityManager->persist($publicacion);
            $entityManager->flush();

            return $this->redirectToRoute('app_muro');
        }

        // Obtener solo las publicaciones del usuario logueado
        $publicaciones = $entityManager->getRepository(Publicacion::class)
            ->findBy(['usuario' => $this->getUser()], ['fechaCreacion' => 'DESC']);

        $usuarios = $entityManager->getRepository(Usuario::class)->findAll();

        // dd(vars: $usuarios);

        return $this->render('muro/index.html.twig', [
            'form' => $form->createView(),
            'publicaciones' => $publicaciones,
            'usuarios' => $usuarios,
            'usuarioActual' => $this->getUser(),
        ]);
    }


    #[Route('/publicar', name: 'app_publicar', methods: ['POST'])]
    public function publicar(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contenido = $request->request->get('contenido');

        if ($contenido) {
            $publicacion = new Publicacion();
            $publicacion->setUsuario($this->getUser());
            $publicacion->setContenido($contenido);
            $publicacion->setFechaCreacion(new \DateTimeImmutable());

            $entityManager->persist($publicacion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_muro');
    }

    #[Route('/publicacion/{id}/eliminar', name: 'app_eliminar_publicacion', methods: ['POST'])]
    public function eliminarPublicacion(Publicacion $publicacion, EntityManagerInterface $entityManager): Response
    {
        // Asegurarse de que el usuario sea el propietario de la publicación
        if ($publicacion->getUsuario() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No tienes permiso para eliminar esta publicación.');
        }

        // Eliminar los comentarios relacionados
        foreach ($publicacion->getComentarios() as $comentario) {
            $entityManager->remove($comentario);
        }

        // Elimina los comentarios hijos de los comentarios principales
        foreach ($publicacion->getComentarios() as $comentario) {
            $this->eliminarComentariosHijos($comentario, $entityManager);
        }
        // Luego eliminar la publicación
        $entityManager->remove($publicacion);
        $entityManager->flush();

        return $this->redirectToRoute('app_muro');
    }

    private function eliminarComentariosHijos(Comentario $comentario, EntityManagerInterface $em)
    {
        // Elimina todos los comentarios hijos
        foreach ($comentario->getComentarios() as $comentarioHijo) {
            $this->eliminarComentariosHijos($comentarioHijo, $em); // Recursión para eliminar los hijos de los hijos
        }

        // Elimina el comentario padre (actual)
        $em->remove($comentario);
    }

    #[Route('/comentario/{id}/eliminar', name: 'app_eliminar_comentario', methods: ['POST'])]
    public function eliminarComentario(Comentario $comentario, EntityManagerInterface $entityManager): Response
    {
        // Asegúrate de que el usuario sea el propietario del comentario
        if ($comentario->getUsuario() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No tienes permiso para eliminar este comentario.');
        }

        // Eliminar los comentarios hijos si existen
        $this->eliminarComentariosHijos($comentario, $entityManager);

        // Eliminar el comentario
        $entityManager->remove($comentario);
        $entityManager->flush();

        return $this->redirectToRoute('app_muro');
    }

    #[Route('/comentario/{id}/eliminar2', name: 'app_eliminar_comentario2', methods: ['POST'])]
    public function eliminarComentario2(Comentario $comentario, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Asegúrate de que el usuario sea el propietario del comentario
        if ($comentario->getUsuario() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No tienes permiso para eliminar este comentario.');
        }

        // Eliminar los comentarios hijos si existen
        $this->eliminarComentariosHijos($comentario, $entityManager);

        // Eliminar el comentario
        $entityManager->remove($comentario);
        $entityManager->flush();

        // Redirigir a la misma página en la que estaba el usuario
        return $this->redirect($request->headers->get('referer'));
    }



    #[Route('/publicacion/{id}/comentar', name: 'app_comentar_publicacion', methods: ['POST'])]
    public function comentar(Publicacion $publicacion, Request $request, EntityManagerInterface $entityManager): Response
    {
        $contenidoComentario = $request->request->get('contenido_comentario');

        if ($contenidoComentario) {
            $comentario = new Comentario();
            $comentario->setPublicacion($publicacion);
            $comentario->setUsuario($this->getUser());
            $comentario->setContenido($contenidoComentario);
            $comentario->setFechaCreacion(new \DateTimeImmutable());

            $entityManager->persist($comentario);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_muro');
    }

    #[Route('/publicacion/{id}/comentar2', name: 'app_comentar_publicacion2', methods: ['POST'])]
    public function comentar2(Publicacion $publicacion, Request $request, EntityManagerInterface $entityManager): Response
    {
        $contenidoComentario = $request->request->get('contenido_comentario');

        if ($contenidoComentario) {
            $comentario = new Comentario();
            $comentario->setPublicacion($publicacion);
            $comentario->setUsuario($this->getUser());
            $comentario->setContenido($contenidoComentario);
            $comentario->setFechaCreacion(new \DateTimeImmutable());

            $entityManager->persist($comentario);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_muro_usuario', ['id' => $publicacion->getUsuario()->getId()]);
    }


    #[Route('/comentario/{id}/responder', name: 'app_responder_comentario', methods: ['POST'])]
    public function responder(Comentario $comentarioPadre, Request $request, EntityManagerInterface $entityManager): Response
    {
        $contenidoComentario = $request->request->get('contenido_comentario');

        if ($contenidoComentario) {
            $comentario = new Comentario();
            $comentario->setComentarioPadre($comentarioPadre);
            $comentario->setUsuario($this->getUser());
            $comentario->setContenido($contenidoComentario);
            $comentario->setFechaCreacion(new \DateTimeImmutable());

            $entityManager->persist($comentario);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_muro');
    }

    #[Route('/muro/{id}', name: 'app_muro_usuario')]
    public function muroUsuario(int $id, EntityManagerInterface $entityManager): Response
    {
        $usuario = $entityManager->getRepository(Usuario::class)->find($id);

        if (!$usuario) {
            throw $this->createNotFoundException('Usuario no encontrado.');
        }

        $publicaciones = $entityManager->getRepository(Publicacion::class)->findBy(
            ['usuario' => $usuario],
            ['fechaCreacion' => 'DESC']
        );

        return $this->render('muro/muro_usuario.html.twig', [
            'usuario' => $usuario,
            'publicaciones' => $publicaciones,
        ]);
    }

}

