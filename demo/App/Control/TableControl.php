<?php
/**
 * @author Dawnc
 * @date   2020-12-24
 */

namespace App\Control;


use App\Exception\AuthException;
use App\Model\Model;
use Wms\Fw\Conf;
use Wms\Fw\Db;

class TableControl extends Control
{

    private   $fields = [];
    private   $table  = "";
    protected $db     = null;

    public function index($table, $id = 0)
    {

        $method = $GLOBALS['REQUEST_METHOD'] ?? 'GET';

        $this->table = $table;
        $model       = Model::get($table);

        $this->db = Db::instance();

        switch ($method) {
            case "GET":
                if ($id) {
                    return $this->find($model, $id);
                } else {
                    return $this->all($model);
                }
            break;
            case "POST":
                return $this->create($model, input());
            break;
            case "PUT":
                return $this->update($model, $id, input());
            break;
            case "DELETE":
                return $this->delete($model, $id);
            break;
        }

    }

    /**
     * @param Model $model
     * @return array
     */
    protected function where($model)
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

            if ($model->hasField($field)) {
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

    /**
     * @param Model $model
     * @return array
     */
    protected function all($model)
    {
        $query = $this->where($model);
        $data  = $model->all($query['where'], $query['page'], $query['size'], $query['sortField'] . " " . $query['sortOrder']);
        return $data;
    }

    protected function find($model, $id)
    {
        return $model->find($id);
    }

    protected function create($model, $data)
    {
        return $model->create($data);
    }

    protected function delete($model, $id)
    {
        return $model->delete($id);
    }

    protected function update($model, $id, $data)
    {
        return $model->update($id, $data);
    }


}
