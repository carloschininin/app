<?php

namespace CarlosChininin\App\Infrastructure\Util;

class DateFormatter
{
    private const array FORMAT_MAP = [
        'd' => 'dd', 'j' => 'd', 'm' => 'MM', 'n' => 'M',
        'Y' => 'yyyy', 'y' => 'yy', 'F' => 'MMMM', 'M' => 'MMM',
        'l' => 'EEEE', 'D' => 'EEE', 'H' => 'HH', 'G' => 'H',
        'h' => 'hh', 'g' => 'h', 'i' => 'mm', 's' => 'ss',
        'A' => 'a', 'a' => 'a'
    ];

    public static function format(
        ?\DateTimeInterface $date,
        string $format = 'd/m/Y',
        string $locale = 'es_PE',
        string $timezone = 'America/Lima'
    ): ?string {
        return $date ? (new \IntlDateFormatter(
            $locale,
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::FULL,
            $timezone,
            \IntlDateFormatter::GREGORIAN,
            strtr($format, self::FORMAT_MAP)
        ))->format($date) : null;
    }
}
