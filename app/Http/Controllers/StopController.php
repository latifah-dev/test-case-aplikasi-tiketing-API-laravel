<?php

namespace App\Http\Controllers;
use App\Models\Stop;
use App\Models\City;
use Illuminate\Http\Request;

class StopController extends Controller
{
    function index() {
        $request=Stop::query()->get();
        return response()->json([
            "status"=>true,
            "message"=>"",
            "data"=>$request
        ]);
    }
    public function show($id)
    {
        $request = Stop::query()->where("id", $id)->first();

        if ($request == null) {
            return response()->json([
                "status" => false,
                "message" => "Stop tidak ditemukan",
                "data" => null
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "",
            "data" => $request
        ]);
    }
    public function showByCity($cityName)
    {
    $city = City::where('name', $cityName)->first();
    if (!$city) {
        return response()->json([
            'status' => false,
            'message' => 'Kota tidak ditemukan',
            'data' => null
        ]);
    }

    $stops = Stop::where('city_id', $city->id)->get();

    if ($stops->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'Tidak ada Stop yang terhubung dengan kota ini',
            'data' => null
        ]);
    }

    return response()->json([
        'status' => true,
        'message' => '',
        'data' => $stops
    ]);
}


    public function store(Request $request)
    {
        $payload = $request->all();

        $request = Stop::query()->create($payload);
        return response()->json([
            "status" => true,
            "message" => "",
            "data" => $request
        ]);
    }
    
    function update($id,Request $request){
        $request = Stop::query()->where('id',$id)->first();
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
        $delete =  Stop::query()->where("id", $id)->delete();
        return response()->json([
            'status' =>true,
            'message' => 'data telah dihapus'
        ]);
    }
}
