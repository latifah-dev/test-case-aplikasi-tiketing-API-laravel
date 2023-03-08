<?php

namespace App\Http\Controllers;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    function index() {
        $request=Role::query()->get();
        return response()->json([
            "status"=>true,
            "message"=>"",
            "data"=>$request
        ]);
    }
    public function show($id)
    {
        $request = Role::query()->where("id", $id)->first();

        if ($request == null) {
            return response()->json([
                "status" => false,
                "message" => "Role tidak ditemukan",
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
        if (!isset($payload["name"])) {
            return response()->json([
                "status" => false,
                "message" => "wajib ada nama",
                "data" => null
            ]);
        }

        $request = Role::query()->create($payload);
        return response()->json([
            "status" => true,
            "message" => "",
            "data" => $request
        ]);
    }
    function update($id,Request $request){
        $request = Role::query()->where('id',$id)->first();
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
        $delete =  Role::query()->where("id", $id)->delete();
        return response()->json([
            'status' =>true,
            'message' => 'data telah dihapus'
        ]);
    }
}
