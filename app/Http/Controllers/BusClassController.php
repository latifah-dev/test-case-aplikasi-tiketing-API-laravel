<?php

namespace App\Http\Controllers;
use App\Models\BusClass;
use App\Models\Bus;
use Illuminate\Http\Request;

class BusClassController extends Controller
{
    function index() {
        $request=BusClass::query()->get();
        return response()->json([
            "status"=>true,
            "message"=>"",
            "data"=>$request
        ]);
    }
    public function show($name)
{
    $request = BusClass::query()->where("name", $name)->first();
        
    if ($request == null) {
        return response()->json([
            "status" => false,
            "message" => "request tidak ditemukan",
            "data" => null
        ]);
    }

    $buses = Bus::query()
        ->join('bus_classes', 'buses.id', '=', 'bus_classes.bus_id')
        ->where('bus_classes.name', $request->name)
        ->get(['buses.*']);

    return response()->json([
        "status" => true,
        "message" => "",
        "data" => $buses
    ]);
}


    public function store(Request $request)
    {
        $payload = $request->all();
        $request = BusClass::query()->create($payload);
        return response()->json([
            "status" => true,
            "message" => "",
            "data" => $request
        ]);
    }
    function update($id,Request $request){
        $request = BusClass::query()->where('id',$id)->first();
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
        $delete =  BusClass::query()->where("id", $id)->delete();
        return response()->json([
            'status' =>true,
            'message' => 'data telah dihapus'
        ]);
    }
}
