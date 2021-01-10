<?php
/**
 * @author Dawnc
 * @date   2020-12-24
 */

namespace App\Control;


use App\Exception\AuthException;
use App\Model\Model;
use Wms\Database\Mysqli;
use Wms\Fw\Conf;
use Wms\Fw\Db;

class SampleFullControl extends RestFullControl
{

    protected $model;

    public function sample($table = null, $id = 0)
    {
        $this->model = Model::get($table);
        return parent::restFull($id);
    }

    public function index()
    {
        $query = $this->where();

        $data = $this->model->all($query['where'], $query['page'], $query['size'], $query['sortField'] . " " . $query['sortOrder']);
        return $data;
    }

    public function show($id)
    {
        return $this->model->find($id);
    }

    public function store()
    {
        return $this->model->create($this->request->data());
    }

    public function update($id)
    {
        return $this->model->update($id, $this->request->data());
    }

    public function destroy($id)
    {
        return $this->model->delete($id);
    }

    /**
     * @param Model $model
     * @return array
     */
    protected function where()
    {

        $get = input(null, []);

        $where = [];
        foreach ($get as $field => $val) {

            /**
             * createdDater = 2020-04-01
             * 条件为   created = 2020-04-01
             * createdDater = 2020-04-01,2020-05-01
             * 条件为   created >= 2020-04-01 AND created <= 2020-04-01
             */
            if (preg_match('/^([a-zA-Z0-9]+)IntDater$/', $field)) {
                $field = str_replace('IntDater', '', $field);
                $val   = explode(",", $val);
                if (count($val) == 2) {
                    //区间查询
                    $where[] = ["AND `{$field}` >= ?", strtotime($val[0])];
                    $where[] = ["AND `{$field}` <= ?", strtotime($val[1])];
                } else {
                    $where[] = ["AND `{$field}` = ?", strtotime($val[0])];
                }
                continue;
            } elseif (preg_match('/^([a-zA-Z0-9]+)Dater$/', $field)) {
                /**
                 * createdDater = 2020-04-01
                 * 条件为   created = 2020-04-01
                 * createdDater = 2020-04-01,2020-05-01
                 * 条件为   created >= 2020-04-01 AND created <= 2020-04-01
                 */
                $field = str_replace('Dater', '', $field);
                $val   = explode(",", $val);
                if (count($val) == 2) {
                    //区间查询
                    $where[] = ["AND `{$field}` >= ?", $val[0]];
                    $where[] = ["AND `{$field}` <= ?", $val[1]];
                } else {
                    $where[] = ["AND `{$field}` = ?", $val[0]];
                }
                continue;
            }

            if ($this->model->hasField($field)) {
                $compareField = strtolower($field);
                if (strpos($compareField, "name") !== false
                    || strpos($compareField, "code") !== false
                    || strpos($compareField, "title") !== false) {
                    $where[] = ["AND `{$field}` LIKE ?l ", $val];
                } else {
                    $where[] = ["AND `{$field}` = ?", $val, $val !== null && $val !== ''];
                }
            }
        }

        $page = input('page', 1);
        $size = input('pageSize', 10);

        $sortField = input('sortField', 'id');
        $sortOrder = input('sortOrder', 'DESC');

        return [
            'where'     => $where,
            'page'      => $page,
            'size'      => $size,
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ];
    }

}
