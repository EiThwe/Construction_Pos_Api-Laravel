<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

class HelperController extends Controller
{
    public static function findAllQuery($Model, $request, $columns)
    {
        return $Model::when($request->has("search"), function ($query) use ($columns, $request) {
            $query->where(function (Builder $builder) use ($columns, $request) {
                $search = $request->search;

                foreach ($columns as $index => $column) {
                    if ($index === 0) {
                        $builder->where($column, 'like', '%' . $search . '%');
                    } else {
                        $builder->orWhere($column, 'like', '%' . $search . '%');
                    }
                }
            });
        })->when($request->has('start_date') && $request->has('end_date'), function ($query) use ($request) {
            $query->whereBetween('created_at', [$request->start_date, Carbon::parse($request->end_date)->addDay()]);
        })->latest("id")
            ->paginate($request->limit ?? 20)
            ->withQueryString();
    }
}
