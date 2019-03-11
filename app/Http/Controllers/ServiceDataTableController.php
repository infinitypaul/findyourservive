<?php

namespace App\Http\Controllers;

use App\Http\Controllers\DataTable\DataTableController;
use App\Service;
use Illuminate\Http\Request;

class ServiceDataTableController extends DataTableController
{

    protected $allowDeletion = true;

    protected $allowCreation = true;

    public function builder()
    {
       return Service::query();
    }

    public function getDisplayableColumns(){
        return [
            'id',
            'title',
            'description',
            'address',
            'city',
            'state',
            'zip_code',
            'latitude',
            'longitude',
            'created_at'
        ];
    }

    public function getCustomColumnNames(){
        return [
            'zip_code' => 'Zip Code',
            'created_at' => 'Date'
        ];
    }

    public function getReadOnlyColumnNames(){
        return [
            'address',
            'city',
            'state',
            'zip_code',
            'latitude',
            'longitude',
        ];
    }

    public function getUpdatableColumns(){
        return [
            'title',
            'description',
            'address',
            'city',
            'state',
            'zip_code',
            'latitude',
            'longitude',
        ];
    }

    public function update($id, Request $request){
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'address' => 'required'
        ]);
        $this->builder->find($id)->update($request->only($this->getUpdatableColumns()));

    }

    public function edit($id, Request $request){
       return $this->builder->where('id', $id)->first($this->getUpdatableColumns());
    }

    /**
     * Create a Record
     * @return void
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request){
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'address' => 'required'
        ]);

        if(!$this->allowCreation){
            return;
        }

        $this->builder->create($request->only($this->getUpdatableColumns()));
    }




}
