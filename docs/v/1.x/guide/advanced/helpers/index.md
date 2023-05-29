---
title: Helper Functions
---

Recursively Difference Two Arrays
---------------------------------------------------------------------------------

Use the `array_diff_assoc_recursive()` method to recursively differentiate two 
arrays:

``` php
array_diff_assoc_recursive($arrayOne, $arrayTwo);
```

::::: details Technical Details
:::: code-group
::: code-group-item Code Example
``` php
$arrayOne = ['a' => "a1", 'c' => "d1"];

$arrayTwo = ['a' => "a2", 'b' => "b2"];

array_diff_assoc_recursive($arrayOne, $arrayTwo);
```
:::
::: code-group-item Result Example
``` php
['c' => "d1"]
```
:::
::::
:::::

I18n Lang
--------------------------------------------------------------------------------

Use the `i18n_lang()` method to translate a locale into a readable string e.g. 
"English":

```php
i18n_lang('en_US');
```

Array Variable Export with Squares
--------------------------------------------------------------------------------

Use the `var_export_with_array_squares()` method to [var_export] arrays using 
square notation:

```php
var_export_with_array_squares($array)
```

::: details Technical Details
**Parameters**:

- **array**: An <u>array</u> that contains the data for export.
- **return**: Optional <u>boolean</u> that determines if the result should be 
  printed (`false`) or returned (`true`). Default is `false`.
:::

<!--                            that's all folks!                            -->

[var_export]: https://www.php.net/manual/en/function.var-export.php