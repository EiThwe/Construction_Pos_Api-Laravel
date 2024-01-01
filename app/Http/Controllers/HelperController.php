<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HelperController extends Controller
{
    // $additionalConditions = [
    //     ['status', '=', 'active'],
    //     ['category', '!=', 'archived'],
    // ];

    // $orderBy = $request->input('order_by', 'id'); 
    // $orderDirection = $request->input('order_direction', 'desc'); 

    public static function findAllQuery($Model, $request, $columns, $additionalConditions = [], $orderBy = 'id', $orderDirection = 'desc')
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
        })->when(!empty($additionalConditions), function ($query) use ($additionalConditions) {
            // Add additional WHERE conditions based on the provided array
            foreach ($additionalConditions as $condition) {
                $query->where($condition[0], $condition[1], $condition[2]);
            }
        })->orderBy($orderBy, $orderDirection)
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
            return response()->json(["message" => $e->getMessage()], 400);
        }
    }

    static public function handleToDateString($originalDateString)
    {
        $carbonDate = Carbon::createFromFormat('d-m-Y', $originalDateString);

        $formattedDate = $carbonDate->toDateString();

        return $formattedDate;
    }

    static public function parseReturnDate($date, $getAlsoTime = false)
    {
        if ($getAlsoTime) {
            return Carbon::parse($date)->format('d M Y h:i A');
        }

        return Carbon::parse($date)->format('d M Y');
    }

    static public function parseReturnImage($image)
    {
        if (is_null($image)) return "https://i.postimg.cc/FzL0bSm3/pngtree-building-and-construction-logo-design-template-image-317780.jpg";

        return asset(Storage::url($image));
    }
}
