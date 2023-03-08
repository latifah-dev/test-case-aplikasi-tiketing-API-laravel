<?php

namespace App\Http\Controllers;
use App\Models\City;
use App\Models\Stop;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::all();

        return response()->json([
            'success' => true,
            'data' => $routes
        ]);
    }

    public function show($id)
    {
        $route = Route::findOrFail($id);
        $busClass = $route->busClass;
        $routeStops = $route->routeStops;

        $stops = [];
        foreach ($routeStops as $routeStop) {
            $stop = Stop::findOrFail($routeStop->stop_id);
            $city = City::findOrFail($stop->city_id);
            $stops[] = [
                'stop_name' => $stop->name,
                'city_name' => $city->name,
                'stop_order' => $routeStop->stop_order
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'route' => $route,
                'bus_class' => $busClass,
                'stops' => $stops
            ]
        ]);
    }

    public function store(Request $request)
    {
        $payload = $request->all();

        $request = Route::query()->create($payload);
        return response()->json([
            "status" => true,
            "message" => "",
            "data" => $request
        ]);
    }
    function update($id,Request $request){
        $request = Route::query()->where('id',$id)->first();
        if($request == null){
            return response()->json([
                "status" =>false,
                "message" => "data tidak ditemukan",
                "data" => null
            ]);
        }
        $request->fill($request->all());
        $request->save();
        return response()->json([
            'status' => true,
            'message' => 'data telah berubah',
            "data"=> $request
        ]);
    }

    function destroy($id){
        $delete =  Route::query()->where("id", $id)->delete();
        return response()->json([
            'status' =>true,
            'message' => 'data telah dihapus'
        ]);
    }
}
