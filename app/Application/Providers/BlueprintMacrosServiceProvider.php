<?php

declare(strict_types=1);

namespace App\Application\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class BlueprintMacrosServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blueprint::macro('primaryString', function (string $columnName = 'id'): void {
            /** @var Blueprint $this */
            $this->string($columnName)->primary();
        });

        Blueprint::macro('nullableStringMorphs', function (string $name, ?string $indexName = null): void {
            /** @var Blueprint $this */
            $this->string("{$name}_type")->nullable();

            $this->string("{$name}_id")->nullable();

            $this->index(["{$name}_type", "{$name}_id"], $indexName);
        });

        Blueprint::macro('stringMorphs', function (string $name, ?string $indexName = null): void {
            /** @var Blueprint $this */
            $this->string("{$name}_type");

            $this->string("{$name}_id");

            $this->index(["{$name}_type", "{$name}_id"], $indexName);
        });
    }
}
