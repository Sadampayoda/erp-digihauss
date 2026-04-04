<?php

namespace App\Http\Controllers;

use App\Models\Export;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExportController extends Controller
{
    public function loading($id)
    {
        return view('dashboard.reports.components.loading', compact('id'));
    }

    public function status($id)
    {
        $export = Export::findOrFail($id);

        return response()->json([
            'status' => $export->status,
            'message' => $export->message,
            'progress' => $export->progress
        ]);
    }

    public function download($id)
    {
        $export = Export::findOrFail($id);

        $path = 'exports/' . $export->file;

        if (!Storage::exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::download($path);
    }
}
