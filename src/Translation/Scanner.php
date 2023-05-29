<?php

namespace Sirthxalot\Laravel\I18n\Translation;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

class Scanner
{
    /**
     * Create a new file translation scanner instance.
     */
    public function __construct(
        protected readonly Filesystem $disk,
        protected readonly array $i18nConfig,
        protected readonly string $langDirectory,
    ) {
        // ...
    }

    /**
     * Get all translation keys used in scan_path directory.
     *
     * It scans the scan_path directories for any translation
     * messages and returns the key. So, each translation key
     * is used within a translation method somewhere.
     *
     * @since  1.0.0
     *
     * @return array  ['i18n::animals.dog' => ""]
     */
    public function findTranslationStrings(): array
    {
        $results = [];

        $matchingPattern =
            '[^\w]'. // Must not start with any alphanum or _
            '(?<!->)'. // Must not start with ->
            '('.implode('|', $this->i18nConfig['translation_methods']).')'. // Must start with one of the functions
            "\(". // Match opening parentheses
            "\s*". // Whitespace before param
            "[\'\"]". // Match " or '
            '('. // Start a new group to match:
            '.+'. // Must start with group
            ')'. // Close group
            "[\'\"]". // Closing quote
            "\s*". // Whitespace after param
            "[\),]";  // Close parentheses or new parameter

        foreach ($this->disk->allFiles($this->i18nConfig['scan_paths']) as $file) {
            if (preg_match_all("/$matchingPattern/siU", $file->getContents(), $matches)) {
                foreach ($matches[2] as $key) {
                    if (preg_match("/(^[a-zA-Z0-9:_-]+([.][^\1) ]+)+$)/siU", $key, $arrayMatches)) {
                        [$file, $k] = explode('.', $arrayMatches[0], 2);
                        $results[$file][$k] = '';

                        continue;
                    } else {
                        $results[$key] = '';
                    }
                }
            }
        }

        return Arr::dot($results);
    }
}
