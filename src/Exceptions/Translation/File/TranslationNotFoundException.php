<?php

namespace Sirthxalot\Laravel\I18n\Exceptions\Translation\File;

use Illuminate\Contracts\Filesystem\FileNotFoundException;

class TranslationNotFoundException extends FileNotFoundException
{
    /**
     * Create a new translation file not found exception instance.
     *
     * @since  1.0.0
     */
    public static function create(string $path): static
    {
        $message = __('The translation file (:path) is missing.', compact('path'));

        return new static($message);
    }
}
