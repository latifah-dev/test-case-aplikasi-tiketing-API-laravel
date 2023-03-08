<?php

namespace App\Http\Controllers;
use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    function index() {
        $request=Bus::query()->get();
        return response()->json([
            "status"=>true,
            "message"=>"",
            "data"=>$request
        ]);
    }
    public function show($id)
    {
        $request = Bus::query()->where("id", $id)->first();

        if ($request == null) {
            return response()->json([
                "status" => false,
                "message" => "bus tidak ditemukan",
                "data" => null
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "",
            "data" => $request
        ]);
    }

    public function store(Request $request)
    {
        $payload = $request->all();
        

        $request = Bus::query()->create($payload);
        return response()->json([
            "status" => true,
            "message" => "",
            "data" => $request
        ]);
    }
    function update($id,Request $request){
        $request = Bus::query()->where('id',$id)->first();
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
        $delete =  Bus::query()->where("id", $id)->delete();
        return response()->json([
            'status' =>true,
            'message' => 'data telah dihapus'
        ]);
    }
}
