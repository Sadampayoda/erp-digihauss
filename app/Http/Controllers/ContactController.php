<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateContactRequest;
use App\Models\Contact;
use App\Traits\ApiResponse;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    use ApiResponse, Validate, HandleErroMessage;
    protected $model;
    public function __construct()
    {
        $this->model = new Contact();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ((bool) $request->select) {
            try {

                $data = $this->model->get();
                if($request->type) {
                    $data->where('type',$request->type);
                }
                return $this->sendSuccess($data,message: 'Berhasil Mendapatkan Kontak');
            } catch (Exception $e) {
                return $this->sendErrors(message: $e);
            }

        }
        return view('dashboard.contacts.index',[
            'contacts' => $this->model->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateContactRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->model->create($request->validated());
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menambahkan Kontak');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $contact = $this->existsWhereId($this->model,$id);

            return $this->sendSuccess($contact,message: 'Berhasil Mendapatkan Kontak');
        } catch (Exception $e) {
            return $this->sendErrors(message: $e);
        }


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Int $id)
    {
        return view('dashboard.contacts.create',[
            'data' => $this->model->find($id),
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateContactRequest $request, int $id)
    {
        try {
            DB::beginTransaction();
            $contact = $this->existsWhereId($this->model,$id);

            $contact->update($request->validated());
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil memperbarui Kontak');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            DB::beginTransaction();
            $contact = $this->existsWhereId($this->model,$id);

            $contact->delete();
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil menghapus Kontak');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendErrors(message: $e);
        }
    }
}
