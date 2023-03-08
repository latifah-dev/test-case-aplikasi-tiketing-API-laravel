<?php

namespace App\Http\Controllers;
use App\Models\Agent;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    function index() {
        $request=Agent::query()->get();
        return response()->json([
            "status"=>true,
            "message"=>"",
            "data"=>$request
        ]);
    }
    public function show($id)
    {
        $request = Agent::query()->where("id", $id)->first();

        if ($request == null) {
            return response()->json([
                "status" => false,
                "message" => "Agent tidak ditemukan",
                "data" => null
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "",
            "data" => $request
        ]);
    }

    public function register(Request $request)
    {
        
        $pengguna = User::query()->where('email',$request->email)->exists();
        if($pengguna != null){
            return response()->json([
                "status" =>false,
                "message" => "email telah digunakan",
                "data" => null
            ]);
        }
        //membuat user baru
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role_id = $request->role_id; //mengambil id role dengan nama "agent"
        $user->save();
        
        if($user->role_id == 1) {
            //membuat data agen baru
            $agent = new Agent;
            $agent->user_id = $user->id;
            $agent->nama = $request->name;
            $agent->telepon = $request->telepon;
            $agent->alamat = $request->alamat;
            $agent->save();
        }
        return response()->json([
            "status" => true,
            "message" => "Agen berhasil ditambahkan",
            "data" => $user, $agent
        ]);
        
    }

    public function login(Request $request)
{

    //authenticate user
    $user = User::query()->where('email', $request->email)->first();
    if ($user && Hash::check($request->password, $user->password)) {
        //user berhasil login
        $token = $user->createToken('login-token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'User berhasil login',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ], 200);
    } else {
        //user gagal login
        return response()->json([
            'status' => false,
            'message' => 'Email atau password salah',
            'data' => null
        ], 401);
    }
}

    function destroy($id){
        $delete =  Agent::query()->where("id", $id)->delete();
        return response()->json([
            'status' =>true,
            'message' => 'data telah dihapus'
        ]);
    }
}
