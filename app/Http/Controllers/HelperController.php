<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        })->when($request->has('filters'), function ($query) use ($request) {
            $filters = explode("_", $request->filters);

            $query->where($filters[0], $filters[1]);
        })->latest("id")
            ->paginate($request->limit ?? 20)
            ->withQueryString();
    }

    static function handleLogoUpload($file, $currentLogo)
    {
        if ($file) {
            // Delete the current logo if it exists
            if ($currentLogo) {
                Storage::disk('public')->delete($currentLogo);
            }

            // Upload the new logo
            $path = $file->store('images', 'public');

            return $path;
        }

        // If no new logo is provided, keep the current one
        return $currentLogo;
    }

    static function transaction(callable $callback)
    {
        try {
            // Start a database transaction
            DB::beginTransaction();

            // Call the provided callback function
            $result = $callback();

            // Commit the transaction if all operations were successful
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            // Rollback the transaction in case of an exception
            DB::rollBack();

            // Re-throw the exception after rollback
            return response()->json(["error" => $e->getMessage()], 400);
        }
    }
}
