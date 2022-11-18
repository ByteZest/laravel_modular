<?php

declare(strict_types=1);

namespace App\Application\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class BlueprintMacrosServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blueprint::macro('sequence', function (string $columnName = 'sequence') {
            /** @var Blueprint $this */
            if (config('database.default') !== 'sqlite') {
                $this->bigIncrements($columnName);
                $this->dropPrimary($columnName);
            }
        });

        Blueprint::macro('ksuid', function (string $columnName = 'id'): void {
            /** @var Blueprint $this */
            $this->string($columnName)->primary();
        });

        Blueprint::macro('nullableKsuidMorphs', function (string $name, ?string $indexName = null): void {
            /** @var Blueprint $this */
            $this->string("{$name}_type")->nullable();

            $this->string("{$name}_id")->nullable();

            $this->index(["{$name}_type", "{$name}_id"], $indexName);
        });

        Blueprint::macro('ksuidMorphs', function (string $name, ?string $indexName = null): void {
            /** @var Blueprint $this */
            $this->string("{$name}_type");

            $this->string("{$name}_id");

            $this->index(["{$name}_type", "{$name}_id"], $indexName);
        });
    }
}
