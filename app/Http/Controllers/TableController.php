<?php

namespace App\Http\Controllers;

use App\Models\ItemDetail;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index(Request $request)
    {
        try {

            $data = collect();

            switch ($request->prefix) {

                case 'detail':

                    if (!$request->search) {
                        return view('table.index', [
                            'labels' => $request->labels ?? [],
                            'data' => collect(),
                            'checkbox' => $request->checkbox,
                            'onEdit' => $request->edit,
                            'onDelete' => $request->delete,
                        ]);
                    }
                    $status = (int) $request->searchParams['status'] ?? null;

                    $data = ItemDetail::where('item_id', $request->search)
                        ->when(!is_null($status), function ($query) use ($status) {
                            $query->where('status', $status);
                        })
                        ->get();
                    break;
                case 'responsibility':
                    if (!$request->search) {
                        return view('table.index', [
                            'labels' => $request->labels ?? [],
                            'data' => collect(),
                            'checkbox' => $request->checkbox,
                            'onEdit' => $request->edit,
                            'onDelete' => $request->delete,
                        ]);
                    }
                    $status = $request->searchParams['status'] ?? null;
                    $userId = (float) $request->searchParams['user_id'] ?? null;

                    $data = ItemDetail::where('item_id', $request->search)
                        ->when($status, function ($query) use ($status) {
                            $query->whereIn('status', $status);
                        })
                        ->when($userId, function ($query) use ($userId) {
                            $query->whereDoesntHave('itemResponsibility', function ($q) use ($userId) {
                                $q->whereDate('assigned_at', today())
                                    ->whereNull('deleted_at');
                            });
                        })
                        ->get();
                    break;

                default:
                    $data = collect();
                    break;
            }
            return view('table.index', [
                'labels' => $request->labels ?? [],
                'data' => $data,
                'checkbox' => $request->checkbox,
                'onEdit' => $request->edit,
                'onDelete' => $request->delete,
            ]);
        } catch (\Throwable $e) {

            return view('table.index', [
                'labels' => $request->labels ?? [],
                'data' => collect(),
                'checkbox' => $request->checkbox,
                'onEdit' => $request->edit,
                'onDelete' => $request->delete,
            ]);
        }
    }
}
