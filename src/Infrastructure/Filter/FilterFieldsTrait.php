<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Filter;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

trait FilterFieldsTrait
{
    public function addPaginationFilterFields(FormBuilderInterface $builder): void
    {
        $builder
            ->add('page', HiddenType::class, [
                'required' => false,
            ])
            ->add('limit', HiddenType::class, [
                'required' => false,
            ]);
    }

    public function addTextSearchFilterFields(FormBuilderInterface $builder, string $label = 'Buscar', string $placeholder = ''): void
    {
        $builder
            ->add('textSearch', TextType::class, [
                'required' => false,
                'label' => $label,
                'attr' => ['placeholder' => $placeholder],
            ]);
    }
}
