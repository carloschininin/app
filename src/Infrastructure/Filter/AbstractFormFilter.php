<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractFormFilter extends AbstractType
{
    use FilterFieldsTrait;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->dtoClass(),
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'form_filter';
    }

    abstract public function dtoClass(): string;
}
