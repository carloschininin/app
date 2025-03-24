<?php

namespace CarlosChininin\App\Infrastructure\Util;

class DateFormatter
{
    public static function format(
        ?\DateTimeInterface $date,
        string $pattern = 'd-m-Y',
        string $locale = 'es_PE',
        $timezone = 'America/Lima',
    ): ?string {
        if (null === $date) {
            return null;
        }

        $formatter = new \IntlDateFormatter(
            $locale,
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::FULL,
            $timezone,
            \IntlDateFormatter::GREGORIAN,
            $pattern
        );

        return $formatter->format($date);
    }
}
