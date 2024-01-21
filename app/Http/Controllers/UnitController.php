<?php

namespace App\Http\Controllers;

use App\Http\Requests\Unit\CreateUnitRequest;
use App\Http\Resources\Unit\UnitDetailResource;
use App\Http\Resources\Unit\UnitResource;
use App\Models\ConversionFactor;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            try {
                $this->authorize("checkPermission", "manager");
            } catch (\Throwable $th) {
                return response()->json(["message" => "လုပ်ပိုင်ခွင့်မရှိပါ"], 403);
            }

            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $units = HelperController::findAllQuery(Unit::class, $request, ["name"]);

        return UnitResource::collection($units);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUnitRequest $request)
    {
        if (!Gate::allows("checkPermission", "")) return response()->json(["message" => "လုပ်ပိုင်ခွင့်မရှိပါ"], 403);

        $unit = Unit::create([
            "name" => $request->name,
            "unit_type_id" => $request->unit_type_id,
            "remark" => $request->remark,
        ]);


        if ($request->conversions) {
            $conversions = [];
            $reverse_conversions = [];

            foreach ($request->conversions as $conversion) {
                array_push($conversions, [
                    "from_unit_id" => $unit->id,
                    "to_unit_id" => Crypt::decrypt($conversion["to_unit_id"]),
                    "value" => $conversion["value"],
                    "created_at" => Date::now(),
                    "updated_at" => Date::now(),
                ]);
            }

            ConversionFactor::insert([...$conversions, ...$reverse_conversions]);
        };

        return response()->json(["message" => "ယူနစ်ပြုလုပ်ခြင်းအောင်မြင်ပါသည်"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $unit = Unit::find(Crypt::decrypt($id));
        if (is_null($unit)) {
            return response()->json(["message" => "ယူနစ်မရှိပါ"], 400);
        }

        return new UnitDetailResource($unit);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $unit = Unit::find(Crypt::decrypt($id));
        if (is_null($unit)) {
            return response()->json(["message" => "ယူနစ်မရှိပါ"], 400);
        }

        $unit->name = $request->name ?? $unit->name;
        $unit->unit_type_id = $request->unit_type_id ?? $unit->unit_type_id;

        $unit->update();

        if ($request->conversions) {

            $conversions = [];
            $reverse_conversions = [];

            foreach ($request->conversions as $conversion) {
                ConversionFactor::where('from_unit_id', Crypt::decrypt($id))->delete();

                array_push($conversions, [
                    "from_unit_id" => $unit->id,
                    "to_unit_id" => Crypt::decrypt($conversion["to_unit_id"]),
                    "value" => $conversion["value"],
                    "created_at" => Date::now(),
                    "updated_at" => Date::now(),
                ]);
            }

            ConversionFactor::insert([...$conversions, ...$reverse_conversions]);
        };

        return response()->json(["message" => "ယူနစ်ပြင်ဆင်ခြင်းအောင်မြင်ပါသည်"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $unit = Unit::find(Crypt::decrypt($id));
        if (is_null($unit)) {
            return response()->json(["message" => "ယူနစ်မရှိပါ"], 400);
        }

        ConversionFactor::where("from_unit_id", $unit->id)
            ->orWhere("to_unit_id", $unit->id)
            ->delete();

        $unit->delete();

        return response()->json(["message" => "ယူနစ်ဖျက်သိမ်းခြင်းအောင်မြင်ပါသည်"]);
    }
}
