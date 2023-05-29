---
title: I18n Events
---

Language Created
--------------------------------------------------------------------------------

**Class**: `Sirthxalot\Laravel\I18n\Events\Language\LanguageCreated`

This event will be dispatched when a new language was created.

| Attribute | Type | Description |
| :-------- | :--- | :---------- |
| `locale` | `string` | The locale e.g. "en_US". |

Language Deleted
--------------------------------------------------------------------------------

**Class**: `Sirthxalot\Laravel\I18n\Events\Language\LanguageDeleted`

This event will be dispatched when a new language was deleted.

| Attribute | Type | Description |
| :-------- | :--- | :---------- |
| `locale` | `string` | The locale e.g. "en_US". |

Translation Created
--------------------------------------------------------------------------------

**Class**: `Sirthxalot\Laravel\I18n\Events\Translation\TranslationCreated`

This event will be dispatched when a new translation was created.

| Attribute | Type | Description |
| :-------- | :--- | :---------- |
| `key` | `string` | The translation key e.g. `i18n::animals.dog`. |
| `locale` | `string` | The locale e.g. "en_US". |
| `message` | `string` | The translation message e.g. "Dogs don't like cats.". |

Translation Updated
--------------------------------------------------------------------------------

**Class**: `Sirthxalot\Laravel\I18n\Events\Translation\TranslationUpdated`

This event will be dispatched when a translation was updated.

| Attribute | Type | Description |
| :-------- | :--- | :---------- |
| `key` | `string` | The translation key e.g. `i18n::animals.dog`. |
| `locale` | `string` | The locale e.g. "en_US". |
| `message_before` | `string` | The translation message e.g. "Dogs don't like cats.". |
| `message_after` | `string` | The new translation message e.g. "Dogs fight for their yard.". |

Translation Deleted
--------------------------------------------------------------------------------

**Class**: `Sirthxalot\Laravel\I18n\Events\Translation\TranslationDeleted`

This event will be dispatched when a translation was deleted.

| Attribute | Type | Description |
| :-------- | :--- | :---------- |
| `key` | `string` | The translation key e.g. `i18n::animals.dog`. |
| `locale` | `string` | The locale e.g. "en_US". |
