<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Twig\Extension;

use CarlosChininin\App\Infrastructure\Util\DateFormatter;
use Twig\Extension\RuntimeExtensionInterface;

class DateRuntime implements RuntimeExtensionInterface
{
    private array $days;
    private array $months;

    public function __construct()
    {
        $this->days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $this->months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    }

    public function dateFilter(?\DateTimeInterface $date, ?string $pattern = 'd-m-Y'): ?string
    {
        if (null === $date) {
            return '';
        }

        return DateFormatter::format($date, $pattern);
    }

    public function dateMediumFilter(?\DateTimeInterface $date): string
    {
        if (null === $date) {
            return '';
        }

        return $date->format('d').' de '.mb_strtoupper($this->months[$date->format('n') - 1]).' del '.$date->format('Y');
    }

    public function dateLargeFilter(?\DateTimeInterface $date): string
    {
        if (null === $date) {
            return '';
        }

        return $this->days[$date->format('w')].', '.$date->format('d').' de '.$this->months[$date->format('n') - 1].' del '.$date->format('Y');
    }

    public function dateFormatFilter(?\DateTimeInterface $date, string $type): string
    {
        if ('F' === $type) {
            return $this->months[$date->format('n') - 1];
        }

        return '';
    }

    public function dateTimeFilter(?\DateTimeInterface $date, ?string $format = 'd-m-Y h:i:s a'): string
    {
        if (null === $date) {
            return '';
        }

        return $date->format($format);
    }

    public function dateTimeLargeFilter(?\DateTimeInterface $date): string
    {
        if (null === $date) {
            return '';
        }

        return $this->days[$date->format('w')].', '.$date->format('d').' de '.$this->months[$date->format('n') - 1].' del '.$date->format('Y').', '.$date->format('h:i:s a');
    }
}
