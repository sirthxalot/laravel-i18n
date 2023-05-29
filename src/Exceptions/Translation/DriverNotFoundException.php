<?php

namespace Sirthxalot\Laravel\I18n\Exceptions\Translation;

use InvalidArgumentException;

class DriverNotFoundException extends InvalidArgumentException
{
    /**
     * Create a driver not found exception instance.
     *
     * @since 1.0.0
     */
    public static function create($driver): static
    {
        $message = __('The translation driver (:driver) is missing.', compact('driver'));

        return new static($message);
    }
}
