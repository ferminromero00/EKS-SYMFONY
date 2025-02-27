<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ClienteController extends AbstractController
{
    #[Route('/cliente/login', name: 'app_cliente_login')]
    public function login(HttpClientInterface $httpClient, Request $request): Response
    {
        // Obtenemos el nombre de usuario y la contraseña de la URL
        $username = $request->query->get('username');
        $password = $request->query->get('password');

        // Llamamos al microservicio de login enviando las credenciales
        $response = $httpClient->request('POST', 'http://localhost:8000/api/login', [
            'json' => [
                'username' => $username,
                'password' => $password
            ]
        ]);

        // Decodificamos la respuesta para obtener el token JWT
        $json = json_decode($response->getContent(), true);

        // Guardamos el token en la sesión
        $request->getSession()->set('token', $json['token']);

        // Devolvemos el token como respuesta al navegador
        return new Response($json['token']);
    }
}
