<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\UserLog;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereNull('deleted_at')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

            $response = [];
            foreach ($users as $user) {
               $response[] = array(
                   'id' => $user->id,
                   'name' => $user->name,
                    'email' => $user->email,
                    'document_number' => $user->document_number,
                    'phone_number' => $user->phone_number,
                    'country' => $user->country
               );
            }

            return response()->json([
                'list' => $response,
            ]);
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'name',
            'email',
            'document_number',
            'phone_number',
            'country'
        ]);

        $validator = Validator::make($data, [
            'name' => 'required',
            'email'=> 'required|email',
            'document_number'=> 'required',
            'phone_number'=> 'required',
            'country'=> 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $emailExist = User::where('email', $data['email'])
            ->first();

        if($emailExist) {
            return response()->json(['error' => [
                'message' => 'Email já está em uso',
                'code' => '001'
            ]], 400);
        }

        $user = User::create([
            'name'=> $data['name'],
            'email'=> $data['email'],
            'document_number'=> $data['document_number'],
            'phone_number'=> $data['phone_number'],
            'country' => $data['country']
        ]);

        if(!$user) {
            return response()->json(['error' => [
                'message' => 'Falha ao realizar cadastro',
                'code' => '002'
            ]], 400);
        }

        return response()->json(['message' => 'Usuario cadastrado com sucesso']);

    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);

            $userLogs = UserLog::where('user_id', $id)
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get();

            $responseLog = [];
            foreach ($userLogs as $userLog) {
               $responseLog[] = array(
                   'id' => $userLog->id,
                   'data_old' => $userLog->data_old,
                   'data_new' => $userLog->data_new,
               );
            }

            return response()->json([
                'user' => $user,
                'log' => $responseLog
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => [
                'message' => 'Falha ao buscar usuario',
                'code' => '001'
            ]], 400);
        }
    }

    public function update($id, Request $request)
    {
       try {

        $data = $request->only([
            'name',
            'email',
            'document_number',
            'phone_number',
            'country'
        ]);

        $user = User::findOrFail($id);

        $userLogOld = UserLog::create([
            'user_id' => $user->id,
            'data_old' => json_encode($user),
            'data_new' => null
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->document_number = $data['document_number'];
        $user->phone_number = $data['phone_number'];
        $user->country = $data['country'];

        $user->update();

        $userLogNew = Userlog::where('id', $userLogOld->id)->update(
            ['data_new' => json_encode($user)]
        );

        return response()->json(['message' => 'Usuario editado com sucesso']);

       } catch (\Throwable $th) {
           return response()->json(['error' => [
                'message' => 'Falha ao buscar usuario',
                'code' => '001'
            ]], 400);
       }
    }

    public function destroy($id)
    {
        {
            try {

             $user = User::findOrFail($id);

             $userLogOld = UserLog::create([
                 'user_id' => $user->id,
                 'data_old' => json_encode($user),
                 'data_new' => null
             ]);

             $user->deleted_at = date("Y-m-d H:i:s");

             $user->update();

             $userLogNew = Userlog::where('id', $userLogOld->id)->update(
                 ['data_new' => json_encode($user)]
             );

             return response()->json(['message' => 'Usuario editado com sucesso']);

            } catch (\Throwable $th) {
                return response()->json(['error' => [
                     'message' => 'Falha ao buscar usuario',
                     'code' => '001'
                 ]], 400);
            }
         }
    }
}
