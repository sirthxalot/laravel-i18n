<?php

if (! function_exists('array_diff_assoc_recursive')) {
    /**
     * Recursively diff two arrays with the ability to use Collections in arrays.
     */
    function array_diff_assoc_recursive(array $arrayOne, array $arrayTwo): array
    {
        $difference = [];

        foreach ($arrayOne as $key => $value) {
            if (is_array($value) || $value instanceof Illuminate\Support\Collection) {
                if (! isset($arrayTwo[$key])) {
                    $difference[$key] = $value;
                } elseif (! (is_array($arrayTwo[$key]) || $arrayTwo[$key] instanceof Illuminate\Support\Collection)) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = array_diff_assoc_recursive($value, $arrayTwo[$key]);
                    if ($new_diff != false) {
                        $difference[$key] = $new_diff;
                    }
                }
            } elseif (! isset($arrayTwo[$key])) {
                $difference[$key] = $value;
            }
        }

        return $difference;
    }
}

if (! function_exists('i18n_lang')) {
    /**
     * Translate a language locale into a readable string for humans.
     *
     * @since  1.0.0
     *
     * @param  string  $language  "en"
     * @param  ?array  $replace  ['country' => "Vereinigte Staaten"]
     * @param  ?string  $locale  "de"
     * @return string  "Englisch (Vereinigte Staaten)"
     */
    function i18n_lang(string $language, ?array $replace = [], ?string $locale = null): string
    {
        $search = 'i18n::languages.'.$language;
        $value = __($search, $replace, $locale);

        if ($search === $value) {
            return $language;
        }

        return $value;
    }
}

if (! function_exists('var_export_with_array_squares')) {
    /**
     * Var export with ability to replace arrays `array()` with
     * square notation `[]`.
     *
     * @since  1.0.0
     *
     * @return string|void
     */
    function var_export_with_array_squares(mixed $expression, bool $return = false)
    {
        $export = var_export($expression, true);
        $export = preg_replace('/^([ ]*)(.*)/m', '$1$1$2', $export);
        $array = preg_split("/\r\n|\n|\r/", $export);
        $array = preg_replace(["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"], [null, ']$1', ' => ['], $array);
        $export = implode(PHP_EOL, array_filter(['['] + $array));
        if ((bool) $return) {
            return $export;
        } else {
            echo $export;
        }
    }
}
