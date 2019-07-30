<?php

namespace App\Repositories;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;
    protected $query;

    /** find item of model by id
     * @param int $id
     * @return mixed
     */
    public function finds(int $id)
    {
        return $this->model->find($id);
    }

    /** get item of model by column
     * @param string $column
     * @param $option
     * @return mixed
     */
    public function findBy(string $column, $option)
    {
        $data = $this->model->where($column, $option);

        return $data;
    }

    /**
     * @param array $condition
     * @return mixed
     */
    public function findByCondition(array $condition)
    {
        return $this->model->where($condition);
    }

    /**
     * @param array $data
     * @return mixed|void
     */
    public function create(array $data)
    {
        $data = $this->removeNotExistColumns($data);
        return $this->model->create($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed|void
     */
    public function update(int $id, array $data)
    {
        $result = $this->model->find($id);
        $data = $this->removeNotExistColumns($data);
        foreach ($data as $key => $value) {
            $result->$key = $value;
        }
        return $result->save();
    }

    /**
     * @param int $id
     * @return mixed|void
     */
    public function delete(int $id)
    {
        return $this->model->delete($id);
    }

    private function removeNotExistColumns($input)
    {
        $tableColumns = $this->getTableColumns();
        foreach ($input as $keyInput => $valueInput) {
            if (!in_array($keyInput, $tableColumns)) {
                unset($input[$keyInput]);
            }
        }
        return $input;
    }

    private function getTableColumns()
    {
        return $this->model->getConnection()->getSchemaBuilder()->getColumnListing($this->model->getTable());
    }
}
