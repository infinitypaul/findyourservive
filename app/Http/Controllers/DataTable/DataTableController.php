<?php

namespace App\Http\Controllers\DataTable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

abstract class DataTableController extends Controller

{
    /**
     * if an entity is allowed to be created
     * @var bool
     */
    protected $allowCreation = true;

    /**
     * if an entity is allowed to be deleted
     * @var bool
     */
    protected $allowDeletion = true;

    /**
     * The entity builder
     * @var Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    abstract public function builder();

    public function __construct(){
        $builder = $this->builder();

       if(!$builder instanceof Builder){
           throw new Exception('Entity Builder Not Instance of builder');
       }

       $this->builder = $builder;
    }

    public function index(Request $request){

        return response()->json([
           'data' => [
               'table' => $this->builder->getModel()->getTable(),
               'displayable' => array_values($this->getDisplayableColumns()),
               'custom_columns' => $this->getCustomColumnNames(),
               'read_only_columns' => $this->getReadOnlyColumnNames(),
               'updatable' => array_values($this->getUpdatableColumns()),
               'records' => $this->getRecords($request),
               'allow' => [
                   'creation' => $this->allowCreation,
                   'deletion' => $this->allowDeletion
               ]
           ]
        ]);
    }

    public function getDisplayableColumns(){
       return array_diff($this->getDatabaseColumnNames(), $this->builder->getModel()->getHidden());
    }

    /**
     * Get Custom Column Name
     *
     * @return array
     */
    public function getCustomColumnNames(){
        return [];
    }

    public function getReadOnlyColumnNames(){
        return [];
    }

    public function getUpdatableColumns(){
        return  $this->getDisplayableColumns();
    }

    public function destroy($ids, Request $request){
        if(!$this->allowDeletion){
            return;
        }
        $this->builder->whereIn('id', explode(',', $ids))->delete();
    }


    protected function getDatabaseColumnNames(){
        return Schema::getColumnListing($this->builder->getModel()->getTable());
    }

    protected function getRecords(Request $request){
        $builder = $this->builder;
        if ($this->hasSearchQuery($request)){
            $builder = $this->buildSearch($builder, $request);
        }
        try{
            return $this->builder->limit($request->limit)->orderBy('id','desc')->select($this->getDisplayableColumns())->paginate($request->limit);
        }catch(QueryException $e){
            return [];
        }

    }

    protected function hasSearchQuery(Request $request){
        return count(array_filter($request->only(['column','operator','value']))) === 3;
    }

    protected function buildSearch(Builder $builder, Request $request){
        $queryParts = $this->resolveQueryParts($request->operator, $request->value);
        return $builder->where($request->column, $queryParts['operator'], $queryParts['value']);
    }


    /**
     * @param $operator
     * @param $value
     *
     * @return mixed
     */
    protected function resolveQueryParts($operator, $value){
        return Arr::get([
           'equals' => [
               'operator' => '=',
               'value' => $value
           ],
            'contains' => [
                'operator' => 'LIKE',
                'value' => "%{$value}%"
            ],
            'starts_with' => [
                'operator' => 'LIKE',
                'value' => "%{$value}"
            ],
            'ends_with' => [
                'operator' => 'LIKE',
                'value' => "{$value}%"
            ],
            'greater_than' => [
                'operator' => '>',
                'value' => $value
            ],
            'less_than' => [
                'operator' => '<',
                'value' => $value
            ]
        ],$operator);
    }
}
