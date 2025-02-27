<?php

namespace App\Controller;

use App\Entity\LineaPedido;
use App\Form\FormLineaPedidoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Pedido;
use App\Form\FormPedidoType;

class PedidosController extends AbstractController
{
    #[Route('/pedidos', name: 'app_pedidos')]
    public function index(): Response
    {
        return $this->render('pedidos/index.html.twig', [
            'titulo' => 'Pedidos',
        ]);
    }

    #[Route('/pedidos/{id}/ver', name: 'app_ver_pedido')]
    public function VerPedido(Pedido $pedido): Response
    {
        dd($pedido);
    }

    #[Route('/pedidos/nuevo', name: 'app_nuevo_pedido')]
    public function nuevoPedido(Request $request, EntityManagerInterface $em): Response
    {
        $pedido = new Pedido();
        $form = $this->createForm(FormPedidoType::class, $pedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pedido->setCliente($this->getUser());
            // Grabamos el pedido
            $em->persist($pedido);
            $em->flush();

            // Redirigimos a la página donde añadir las líneas
            return $this->redirectToRoute('app_add_linea_pedido', ['id' => $pedido->getId()]);
        }

        return $this->render("pedidos/nuevopedido.html.twig", [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/pedidos/{id}/add-linea', name: 'app_add_linea_pedido')]
    public function addLineaPedido(Pedido $pedido, EntityManagerInterface $em, Request $request)
    {

        $lineaPedido = new LineaPedido();
        $form = $this->createform(FormLineaPedidoType::class, $lineaPedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Le asociamos el pedido
            $lineaPedido->setPedido($pedido);
            // Grabamos la línea de pedido
            $em->persist($lineaPedido);
            $em->flush();

            return $this->redirectToRoute('app_pedidos');

        }

        return $this->render('pedidos/addlinea.html.twig', [
            'pedido' => $pedido,
            'form' => $form,
        ]);
    }
}