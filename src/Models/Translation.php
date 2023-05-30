<?php

namespace Sirthxalot\Laravel\I18n\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Sirthxalot\Laravel\I18n\Contracts\Models\TranslationContract;
use Sirthxalot\Laravel\I18n\Database\Factories\TranslationFactory;

class Translation extends Model implements TranslationContract
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['locale', 'key', 'message'];

    /**
     * Create a new translation model instance.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = config('i18n.database.connection');
        $this->table = config('i18n.database.tables.translations');
    }

    /**
     * Get the language that belongs to the translation.
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'locale');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): TranslationFactory
    {
        return TranslationFactory::new();
    }
}
