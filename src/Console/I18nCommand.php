<?php

namespace Sirthxalot\Laravel\I18n\Console;

use Illuminate\Console\Command;
use Sirthxalot\Laravel\I18n\Translation;

class I18nCommand extends Command
{
    /**
     * Create a new i18n command instance.
     *
     * @since  1.0.0
     */
    public function __construct(protected readonly Translation $i18n)
    {
        parent::__construct();
    }
}
