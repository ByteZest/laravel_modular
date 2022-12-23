<?php

declare(strict_types=1);

namespace App\Infrastructure\Traits;

use App\Application\Exceptions\InvalidPrimaryKeyException;

trait HasKsuids
{
    /**
     * Generate a primary UUID for the model.
     *
     * @return void
     */
    public static function bootHasKsuids(): void
    {
        static::creating(function (self $model) {
            foreach ($model->uniqueIds() as $column) {
                if (empty($model->{$column})) {
                    $model->{$column} = $model->newUniqueId();
                } else {
                    if (! validateKsuid((string) $model->getKey(), self::class)) {
                        throw InvalidPrimaryKeyException::throw((string) $model->getKey(), self::class);
                    }
                }
            }
        });
    }

    /**
     * Generate a new UUID for the model.
     *
     * @return string
     */
    public function newUniqueId(): string
    {
        return ksuid(self::class);
    }

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array
     */
    public function uniqueIds(): array
    {
        return [$this->getKeyName()];
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType(): string
    {
        if (in_array($this->getKeyName(), $this->uniqueIds())) {
            return 'string';
        }

        return $this->keyType;
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing(): bool
    {
        if (in_array($this->getKeyName(), $this->uniqueIds())) {
            return false;
        }

        return $this->incrementing;
    }
}
