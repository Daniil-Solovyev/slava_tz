<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Jobs\ProcessExcelFile;
use App\Http\Requests\UploadRequest;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function showForm()
    {
        return view('upload');
    }

    /**
     * @param \App\Http\Requests\UploadRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(UploadRequest $request)
    {
        $file = $request->file('file');
        $path = $file->store('imports');

        $full_path = Storage::path($path);

        $progress_key = 'progress_key: ' . Str::uuid();
        $progress_url = route('get-progress', ['key' => $progress_key]);

        ProcessExcelFile::dispatch($full_path, $progress_key);

        return redirect()->back()->with([
            'success' => 'Файл успешно загружен! Обработка начата.',
            'progress_url' => $progress_url,
        ]);
    }
}
