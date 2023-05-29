<?php

namespace Sirthxalot\Laravel\I18n\Console;

class ListLanguagesCommand extends I18nCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'i18n:list-languages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all languages available on the current driver';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $table = [];
        $allLanguages = $this->i18n->allLanguages();

        foreach ($allLanguages as $locale => $name) {
            $table[] = ['locale' => $locale, 'name' => $name];
        }

        $this->newLine();

        $this->table([__('LOCALE'), __('NAME')], $table);
    }
}
