<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use App\Helpers\Helper;

class CustomQueryStatementServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Builder::macro('whereLike', function ($attributes, string $searchTerm) {
            $searchTerm = Helper::checkSpecialCharacter($searchTerm);
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (array_wrap($attributes) as $attribute) {
                    $query->when(
                        str_contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerm) {
                                $query->where($relationAttribute, 'LIKE', "%{$searchTerm}%");
                            });
                        },
                        function (Builder $query) use ($attribute, $searchTerm) {
                            $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                        }
                    );
                }
            });

            return $this;
        });
        Builder::macro('whereLikeRaw', function ($attributes, string $searchTerm) {
            $searchTerm = Helper::checkSpecialCharacter($searchTerm);
            $this->whereRaw("UPPER(" . $attributes . ") LIKE UPPER ('%{$searchTerm}%')");

            return $this;
        });

        QueryBuilder::macro('whereLike', function ($attributes, string $searchTerm) {
            $searchTerm = Helper::checkSpecialCharacter($searchTerm);
            $this->where(function (QueryBuilder $query) use ($attributes, $searchTerm) {
                foreach (array_wrap($attributes) as $attribute) {
                    $query->when(
                        str_contains($attribute, '.'),
                        function (QueryBuilder $query) use ($attribute, $searchTerm) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->orWhereHas($relationName, function (QueryBuilder $query) use ($relationAttribute, $searchTerm) {
                                $query->where($relationAttribute, 'LIKE', "%{$searchTerm}%");
                            });
                        },
                        function (QueryBuilder $query) use ($attribute, $searchTerm) {
                            $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                        }
                    );
                }
            });

            return $this;
        });
        
        QueryBuilder::macro('whereLikeRaw', function ($attributes, string $searchTerm) {
            $searchTerm = Helper::checkSpecialCharacter($searchTerm);
            $this->whereRaw("UPPER(" . $attributes . ") LIKE UPPER ('%{$searchTerm}%')");

            return $this;
        });
    }

    public function register()
    {

    }
}
