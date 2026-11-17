<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';

    protected $primaryKey = 'pelanggan_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'birthday',
        'gender',
        'email',
        'phone',
    ];

    /**
     * Filter pelanggan by the configured request parameters.
     *
     * @param  array<int, string>  $filterableColumns
     */
    public function scopeFilter(Builder $query, Request $request, array $filterableColumns): Builder
    {
        foreach ($filterableColumns as $column) {
            if ($request->filled($column)) {
                $query->where($column, $request->input($column));
            }
        }

        return $query;
    }

    /**
     * Search the configured columns for the request's search term.
     *
     * @param  array<int, string>  $columns
     */
    public function scopeSearch(Builder $query, Request $request, array $columns): Builder
    {
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function (Builder $query) use ($search, $columns): void {
                foreach ($columns as $column) {
                    $query->orWhere($column, 'LIKE', "%{$search}%");
                }
            });
        }

        return $query;
    }
}
