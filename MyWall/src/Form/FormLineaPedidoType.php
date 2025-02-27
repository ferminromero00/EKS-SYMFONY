<?php

namespace App\Form;

use App\Entity\Articulo;
use App\Entity\LineaPedido;
use App\Entity\Pedido;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormLineaPedidoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cantidad')
            // ->add('pedido', EntityType::class, [
            //     'class' => Pedido::class,
            //     'choice_label' => 'id',
            // ])
            ->add('articulo', EntityType::class, [
                'class' => Articulo::class,
                'choice_label' => 'nombre',
            ])
            ->add('agregar', SubmitType::class, ['label' => 'Añadir linea de articulos'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LineaPedido::class,
        ]);
    }
}
