<?php

namespace App\Http\Controllers;

use App\Models\Row;
use Illuminate\Http\Request;
use App\Http\Resources\RowResource;
use Illuminate\Support\Facades\Redis;

class RowController extends Controller
{
    public function index()
    {
        $rows = Row::orderBy('date')->get();

        $grouped_rows = $rows->groupBy('date');

        $result = $grouped_rows->map(function ($rows) {
            return RowResource::collection($rows);
        });

        return response()->json($result);
    }

    public function getProgress(Request $request)
    {
        $progress_key = $request->input('key');
        $progress = Redis::get($progress_key);

        return response()->json([
            'progress' => (int)$progress,
        ]);
    }
}
