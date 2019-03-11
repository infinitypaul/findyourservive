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
     * If an entity is allowed to be created.
     *
     * @var boolean
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

    //abstract public function builder();

    /**
     * Create the controller, check builder method and assign
     * to the builder property.
     *
     * @return void
     */
    public function __construct(){
        if (!method_exists($this, 'builder')) {
            throw new Exception('No entity builder method defined.');
        }

        if (!($this->builder = $this->builder()) instanceof Builder) {
            throw new Exception("Entity builder not instance of Builder.");
        }
    }

    /**
     * Show a list of entities.
     *
     * @return Illuminate\Http\JsonResponse
     */
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

    /**
     * Get the columns that are allowed to be displayed.
     *
     * @return array
     */
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


    /**
     * Get Read Only Column Name
     *
     * @return array
     */
    public function getReadOnlyColumnNames(){
        return [];
    }

    /**
     * Get the columns that are allowed to be updated.
     *
     * @return array
     */
    public function getUpdatableColumns(){
        return  $this->getDisplayableColumns();
    }



    /**
     * Delete an entity.
     *
     * @param  integer  $id
     * @param  Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function destroy($ids, Request $request){
        if(!$this->allowDeletion){
            return;
        }
        $this->builder->whereIn('id', explode(',', $ids))->delete();
    }



    /**
     * Get the database column names for the entity.
     *
     * @return array
     */
    protected function getDatabaseColumnNames(){
        return Schema::getColumnListing($this->builder->getModel()->getTable());
    }



    /**
     * Get records to be used for output.
     *
     * @param  Request $request
     * @return Illuminate\Support\Collection
     */
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

    /**
     * Create an entity.
     *
     * @param  Request $request
     * @return Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$this->allowCreation) {
            return;
        }

        $this->builder->create($request->only($this->getUpdatableColumns()));
    }




    /**
     * Update an entity.
     *
     * @param  integer  $id
     * @param  Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $this->builder->find($id)->update($request->only($this->getUpdatableColumns()));
    }



    /**
     * If the request has the columns required to search.
     *
     * @param  Request $request
     * @return boolean
     */
    protected function hasSearchQuery(Request $request){
        return count(array_filter($request->only(['column','operator','value']))) === 3;
    }



    /**
     * Build the search.
     *
     * @param  Builder $builder
     * @param  Request $request
     *
     * @return Builder
     */
    protected function buildSearch(Builder $builder, Request $request){
        $queryParts = $this->resolveQueryParts($request->operator, $request->value);
        return $builder->where($request->column, $queryParts['operator'], $queryParts['value']);
    }




    /**
     * Resolve the given operator to perform a query.
     *
     * @param   $operator
     * @param  $value
     *
     * @return string
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
