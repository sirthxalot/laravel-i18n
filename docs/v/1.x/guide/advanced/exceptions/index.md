---
title: I18n Exceptions
---

Translation Driver Not Found
--------------------------------------------------------------------------------

**Class**: `Sirthxalot\Laravel\I18n\Exceptions\Translation\DriverNotFoundException`

This exception will be thrown if the [`TranslationManager`] could not resolve 
the driver method.

**Solution**:  
Checkout the `i18n.driver` configuration for any misspellings.  
Allowed values are "file" or "database".

Translation File Not Found
--------------------------------------------------------------------------------

**Class**: `Sirthxalot\Laravel\I18n\Exceptions\Translation\File\TranslationNotFoundException`

This exception will be thrown if the [`Filesystem`] could not read, write or 
delete a translation file.

**Solution**:  
Checkout the path found within the exception message and try to create the file 
or directory by your own.

<!--                            that's all folks!                            -->

[`TranslationManager`]: # "Sirthxalot\Laravel\I18n\Translation\Manager"
[`Filesystem`]: # "Illuminate\Contracts\Filesystem"