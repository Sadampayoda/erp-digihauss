<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateItemRequest;
use App\Models\Brand;
use App\Models\Items;
use App\Models\Series;
use App\Traits\ApiResponse;
use App\Traits\AutoCodeTransaction;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemsController extends Controller
{
    use ApiResponse, Validate, HandleErroMessage, AutoCodeTransaction;
    /**
     * Display a listing of the resource.
     */
    protected $model;

    public function __construct()
    {
        $this->model = new Items();
    }
    public function index(Request $request)
    {
        if ((bool) $request->advance_sale) {
            try {
                $items = $this->model->whereIn('id',$request->items)
                    ->get();

                return $this->sendSuccess($items, message: 'Berhasil menambahkan barang');
            } catch (Exception $e) {
                return $this->sendErrors(message: $e);
            }
        }
        return view('dashboard.items.index', [
            'items' => Items::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateItemRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')
                    ->store('items/main', 'public');
            }

            if ($request->hasFile('images')) {

                $multipleImages = [];

                foreach ($request->file('images') as $file) {
                    $multipleImages[] = $file->store('items/gallery', 'public');
                }

                $data['images'] = $multipleImages;
            }

            $this->model->create($data);
            DB::commit();
            return $this->sendSuccess(message: 'Berhasil Menambahkan Barang');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Items $items)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $data = Items::find($id);
        return view('dashboard.items.create', [
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateItemRequest $request, int $id)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $item = $this->existsWhereId($this->model, $id);

            $data['status'] = $request->has('status');

            if ($request->hasFile('image')) {

                if ($item->image && Storage::disk('public')->exists($item->image)) {
                    Storage::disk('public')->delete($item->image);
                }

                $data['image'] = $request->file('image')
                    ->store('items/main', 'public');
            }

            if ($request->hasFile('images')) {
                if ($item->images) {
                    foreach (json_decode($item->images, true) as $oldImage) {
                        if (Storage::disk('public')->exists($oldImage)) {
                            Storage::disk('public')->delete($oldImage);
                        }
                    }
                }

                $multipleImages = [];

                foreach ($request->file('images') as $file) {
                    $multipleImages[] = $file->store('items/gallery', 'public');
                }

                $data['images'] = $multipleImages;
            }

            $item->update($data);

            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Mengupdate Barang');
        } catch (QueryException $e) {

            DB::rollBack();

            return $this->sendErrors(
                message: $this->handleDatabaseError($e)
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            DB::beginTransaction();

            $item = $this->existsWhereId($this->model, $id);

            if ($item->image && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }

            if ($item->images) {
                foreach (json_decode($item->images, true) as $image) {
                    if (Storage::disk('public')->exists($image)) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }

            $item->delete();

            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menghapus Barang');
        } catch (QueryException $e) {

            DB::rollBack();

            return $this->sendErrors(
                message: $this->handleDatabaseError($e)
            );
        }
    }
}
