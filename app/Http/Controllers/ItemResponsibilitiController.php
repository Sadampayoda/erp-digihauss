<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateItemResponsibilitiRequest;
use App\Models\ItemDetail;
use App\Models\ItemResponsibility;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemResponsibilitiController extends Controller
{
    use ApiResponse, Validate, HandleErroMessage;
    protected $salesInvoiceRepo, $model, $setupColumn;

    public function __construct()
    {

        $this->model = new ItemResponsibility();
        $this->setupColumn = [
            'image' => ['label' => 'Gambar', 'type' => 'image'],
            'detail_id' => ['label' => '', 'type' => 'hidden'],
            'name' => ['label' => 'Nama Produk'],
            'serial_number' => ['label' => 'Seri'],
            'color' => ['label' => 'Warna'],
            'sale_price' => ['label' => 'Harga Jual', 'type' => 'number'],
            'purchase_price' => ['label' => 'Harga Beli', 'type' => 'number'],
            'service' => ['label' => 'Service', 'type' => 'number'],
            'action' => ['label' => 'Action', 'delete' => true],
            'item_detail_id' => ['label' => ' ', 'type' => 'hidden'],
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = \App\Models\User::leftJoin('item_responsibilitis', function ($join) {
            $join->on('users.id', '=', 'item_responsibilitis.user_id')
                ->whereNull('item_responsibilitis.deleted_at')
                ->whereDate('item_responsibilitis.assigned_at', today());
        })
            ->select(
                'users.id',
                'users.name',
                DB::raw('COUNT(item_responsibilitis.id) as total_unit')
            )
            ->groupBy('users.id', 'users.name')
            ->get();
        return view('dashboard.inventory.item_responsibility.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.inventory.item_responsibility.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateItemResponsibilitiRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $this->model->create($data);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Modifikasi Penanggung Jawab');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ItemResponsibility $itemResponsibility)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $user = $this->existsWhereId(
            new User(),
            $id,
            [
                'itemResponsibility' => function ($q) {
                    $q->whereDate('assigned_at', Carbon::now());
                },
                'itemResponsibility.item',
                'itemResponsibility.itemDetail'
            ]
        );

        $itemDetail = ItemDetail::whereIn('status', [0,1])
            ->whereDoesntHave('itemResponsibility', function ($q) {
                $q->whereDate('assigned_at', today())
                ->whereNull('deleted_at');
            })
            ->get();

        return view('dashboard.inventory.item_responsibility.create', [
            'data' => $user,
            'items' => $itemDetail,
            'setupColumn' => $this->setupColumn,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateItemResponsibilitiRequest $request, int $id)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $this->existsWhereId(new User(), $id);

            $userId = $data['user_id'];
            $assignedAt = $data['assigned_at'] ?? now();

            $incomingDetailIds = collect($data['items'])
                ->pluck('item_detail_id')
                ->toArray();

            $existing = $this->model->where('user_id', $userId)
                ->pluck('item_detail_id')
                ->toArray();

            $toInsert = array_diff($incomingDetailIds, $existing);
            $toDelete = array_diff($existing, $incomingDetailIds);

            if (!empty($toInsert)) {
                $insertData = collect($data['items'])
                    ->whereIn('item_detail_id', $toInsert)
                    ->map(function ($item) use ($userId, $assignedAt) {
                        return [
                            'user_id' => $userId,
                            'item_id' => $item['item_id'],
                            'item_detail_id' => $item['item_detail_id'],
                            'assigned_at' => $assignedAt,
                        ];
                    })
                    ->values()
                    ->toArray();

                foreach ($insertData as $dataInsert) {
                    $this->model->create($dataInsert);
                }
            }

            if (!empty($toDelete)) {
                $records = $this->model->where('user_id', $userId)
                    ->whereIn('item_detail_id', $toDelete)
                    ->get();

                foreach ($records as $record) {
                    $detail = $record->itemDetail;

                    $this->allowItemDetail($detail);
                    $record->delete();
                }
            }

            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Modifikasi Penanggung Jawab');
        } catch (QueryException $e) {
            dd($e);
            DB::rollBack();
            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemResponsibility $itemResponsibiliti)
    {
        //
    }
}
