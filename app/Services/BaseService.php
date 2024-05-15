<?php

namespace App\Services;

/**
* Base Class for handles the common methods that using a lot in Services
* Autor : Tuan Nguyen
*/
abstract class BaseService
{
    public $_repository;

    // get all
    public function all()
    {
        return $this->_repository->all();
    }

    // paginate
    public function paginated($input = 10)
    {
        return $this->_repository->paginated($input);
    }

    // create a model -> save to db
    public function create(array $input)
    {
        return $this->_repository->create($input);
    }

    // find a model
    public function find($id)
    {
        return $this->_repository->find($id);
    }

    // update model
    public function update($id, array $input)
    {
        return $this->_repository->update($id, $input);
    }

    // delete a model
    public function destroy($id)
    {
        return $this->_repository->destroy($id);
    }
}