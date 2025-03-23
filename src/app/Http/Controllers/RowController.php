<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class RowController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $rows = DB::table('rows')
            ->select('id', 'name', 'date')
            ->orderBy('date')
            ->get()
            ->groupBy('date');

        return response()->json($rows);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProgress(Request $request)
    {
        $progress_key = $request->input('key');
        $progress = Redis::get($progress_key);

        return response()->json([
            'progress' => (int)$progress,
        ]);
    }
}
