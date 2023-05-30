<?php

namespace Sirthxalot\Laravel\I18n\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Sirthxalot\Laravel\I18n\Contracts\Models\LanguageContract;
use Sirthxalot\Laravel\I18n\Database\Factories\LanguageFactory;

class Language extends Model implements LanguageContract
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['locale'];

    /**
     * Create a new language model instance.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = config('i18n.database.connection');
        $this->table = config('i18n.database.tables.languages');
    }

    /**
     * Get the language's name e.g. "English".
     */
    public function getNameAttribute(): string
    {
        return i18n_lang($this->locale);
    }

    /**
     * Get all translations for the language.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class, 'locale', 'locale');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return LanguageFactory::new();
    }
}
