<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Utils;

use App\Models\UserLog;

class UserLogController extends Controller
{
    public function index($start, $limit, Request $request)
    {
        $initialDate = $start.' 00:00:00';
        $finalDate = $limit.' 23:59:59';

        $userLogs = UserLog::whereBetween('created_at', [$initialDate, $finalDate])
        ->orderBy('created_at', 'DESC')
        ->get();

        $response = [];
        foreach ($userLogs as $userLog) {
           $response[] = array(
            'user_id' => $userLog->id,
            'data_old' => $userLog->data_old,
            'data_new' => $userLog->data_new
           );
        }

         return  Utils::paginate_without_key($response);
    }

    public function show($id, $start, $limit, Request $request)
    {
        $initialDate = $start.' 00:00:00';
        $finalDate = $limit.' 23:59:59';

        $userLogs = UserLog::where('user_id', $id)
            ->whereBetween('created_at', [$initialDate, $finalDate])
            ->orderBy('created_at', 'DESC')
            ->get();

        $response = [];
        foreach ($userLogs as $userLog) {
           $response[] = array(
            'user_id' => $userLog->id,
            'data_old' => $userLog->data_old,
            'data_new' => $userLog->data_new
           );
        }

         return  Utils::paginate_without_key($response);
    }
}
