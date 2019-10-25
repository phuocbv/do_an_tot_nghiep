<?php

/**
 * Created by PhpStorm.
 * User: da
 * Date: 19/09/2017
 * Time: 01:04
 */
namespace App\Repositories\Contracts;

interface BaseRepositoryInterface
{
    public function currentUser();

    public function getModel();

    public function count();

    public function all();

    public function find($id);

    public function findBy($column, $option);

    public function findWhere($array = [], $column = ['*']);

    public function findWhereIn($field, $array = [], $column = ['*']);

    public function paginate($limit);

    public function create($inputs = []);

    public function insert($inputs = []);

    public function update($inputs = [], $id);

    public function delete($ids);

    public function show($id);
}
