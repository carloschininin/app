<?php

declare(strict_types=1);

namespace CarlosChininin\App\Infrastructure\Twig;

use DateTimeInterface;
use Twig\Extension\RuntimeExtensionInterface;

class DateRuntime implements RuntimeExtensionInterface
{
    private array $days;
    private array $months;

    public function __construct()
    {
        $this->days = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado'];
        $this->months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    }

    public function dateFilter(?DateTimeInterface $date, ?string $format = 'd-m-Y'): string
    {
        if (null === $date) {
            return '';
        }

        return $date->format($format);
    }

    public function dateMediumFilter(?DateTimeInterface $date): string
    {
        if (null === $date) {
            return '';
        }

        return $date->format('d').' de '.mb_strtoupper($this->months[$date->format('n') - 1]).' del '.$date->format('Y');
    }

    public function dateLargeFilter(?DateTimeInterface $date): string
    {
        if (null === $date) {
            return '';
        }

        return $this->days[$date->format('w')].', '.$date->format('d').' de '.$this->months[$date->format('n') - 1].' del '.$date->format('Y');
    }

    public function dateFormatFilter(?DateTimeInterface $date, string $type): string
    {
        if ('F' === $type) {
            return $this->months[$date->format('n') - 1];
        }

        return '';
    }
}