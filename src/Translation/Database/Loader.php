<?php

namespace Sirthxalot\Laravel\I18n\Translation\Database;

use Illuminate\Contracts\Translation\Loader as LoaderInterface;
use Illuminate\Support\Collection;
use Sirthxalot\Laravel\I18n\Translation;

class Loader implements LoaderInterface
{
    /**
     * Create a new database translation loader instance.
     */
    public function __construct(protected readonly Translation $i18n)
    {
        // ...
    }

    /**
     * Add a new JSON path to the loader.
     *
     * @param  string  $path
     */
    public function addJsonPath($path): void
    {
        // ...
    }

    /**
     * Add a new namespace to the loader.
     *
     * @param  string  $namespace
     * @param  string  $hint
     */
    public function addNamespace($namespace, $hint): void
    {
        // ...
    }

    /**
     * Load the messages for the given locale.
     *
     * The loader is responsible for returning the array of
     * language lines for the given namespace, group, and locale.
     * We'll set the lines in this array of lines that have already
     * been loaded so that we can easily access them.
     *
     * @param  string  $locale  "en_US"
     * @param  string  $group  "*"
     * @param  string  $namespace  "*"
     */
    public function load($locale, $group, $namespace = null): array
    {
        $allTranslations = $this->i18n->allTranslations();

        if (! array_key_exists($locale, $allTranslations)) {
            return [];
        }

        if ($group == '*' && $namespace == '*') {
            $allTranslations = Collection::make($allTranslations);

            return $allTranslations->get($locale);
        }

        return [];
    }

    /**
     * Get an array of all the registered namespaces.
     */
    public function namespaces(): array
    {
        return [];
    }
}
