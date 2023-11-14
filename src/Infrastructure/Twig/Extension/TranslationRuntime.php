<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Twig\Extension;

use CarlosChininin\App\Infrastructure\Cache\BaseCache;
use Statickidz\GoogleTranslate;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\RuntimeExtensionInterface;

class TranslationRuntime implements RuntimeExtensionInterface
{
    public const CACHE_TAG = 'GTRANS_';

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly BaseCache $baseCache,
    ) {
    }

    public function googleTranslate(?string $text, string $source = 'es'): string
    {
        if (null === $text || '' === $text) {
            return '';
        }

        $target = $this->translator->getLocale();
        if ($target === $source) {
            return $text;
        }

        if ($text !== ($textTranslated = $this->translator->trans($text))) {
            return $textTranslated;
        }

        return $this->inCache($source, $target, $text);
    }

    private function inCache(string $source, string $target, string $text): string
    {
        $key = self::CACHE_TAG.$source.$target.'_'.md5($text);

        return $this->baseCache->get($key, function () use ($source, $target, $text) {
            return GoogleTranslate::translate($source, $target, $text);
        }, [self::CACHE_TAG], BaseCache::CACHE_TIME_LONG);
    }
}
