<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;


interface Contracts
{

    /**
     * Search data
     * @return mixed
     */
    public function search(Request $request);

    /**
     * Create new resource
     * @param array $data
     * @return void
     */
    public function create(array $data);

    /**
     * Update new resource
     * @param string $id
     * @param array $data
     * @return void
     */
    public function update(string $id, array $data);

    /**
     * Retrieve resource by $id
     * @param string $id
     * @return void
     */
    public function find(string $id);

    /**
     * Destroy resource by id
     * @param string $id
     * @return void
     */
    public function delete(string $id);

}