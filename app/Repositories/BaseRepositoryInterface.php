<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function fetchAll();

    public function fetchAllByCondition(array $condition, $orderBy = null);

    public function fetchByCondition(array $condition);

    public function fetchPaging(array $condition, $itemPerpage = 15, $oderBy = null);

    public function fetch($id);

    public function update(array $condition, $data);

    public function updateOrCreate(array $condition, $data);

    public function store($data);

    public function destroy($id);

    public function softDeleted($id);

    public function getCount();

    public function fetchTrash($id);
}