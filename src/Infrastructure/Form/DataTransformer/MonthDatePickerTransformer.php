<?php

declare(strict_types=1);

namespace CarlosChininin\App\Infrastructure\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class MonthDatePickerTransformer implements DataTransformerInterface
{
    private static array $months = [
        'Enero' => '01', 'Febrero' => '02', 'Marzo' => '03',
        'Abril' => '04', 'Mayo' => '05', 'Junio' => '06',
        'Julio' => '07', 'Agosto' => '08', 'Septiembre' => '09',
        'Octubre' => '10', 'Noviembre' => '11', 'Diciembre' => '12'
    ];

    /**
     * {@inheritdoc}
     *
     * @param \DateTimeInterface|null $value
     */
    public function transform(mixed $value): string
    {
        if (null === $value) {
            return '';
        }

        if (!$value instanceof \DateTimeInterface) {
            throw new TransformationFailedException('Se esperaba un objeto DateTime');
        }

        $mes = array_search($value->format('m'), self::$months);
        return sprintf('%s-%s', $mes, $value->format('Y'));
    }

    /**
     * {@inheritdoc}
     *
     * @param string|null $value
     */
    public function reverseTransform(mixed $value): ?\DateTimeInterface
    {
        if (empty($value)) {
            return null;
        }

        $parts = explode('-', $value);
        if (count($parts) !== 2) {
            throw new TransformationFailedException('Formato inválido');
        }

        $month = trim($parts[0]);
        $year = trim($parts[1]);

        if (!isset(self::$months[$month])) {
            throw new TransformationFailedException('Mes inválido');
        }

        try {
            return new \DateTime(sprintf('%s-%s-01', $year, self::$months[$month]));
        } catch (\Exception) {
            throw new TransformationFailedException('Fecha inválida');
        }
    }
}