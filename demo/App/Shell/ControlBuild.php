<?php
/**
 * @author Dawnc
 * @date   2021-01-10
 */

namespace App\Shell;


use Wms\Fw\Db;
use Wms\Lib\ShellHandle;

class ControlBuild extends ShellHandle
{
    protected function handle($param = null)
    {
        $table = arr_get($param, 0);

        if (!$table) {
            return $this->log('没有指定table');
        }

        $db   = Db::instance();
        $data = $db->getData("desc `$table`");

        $controlName = ucfirst(strtolower($table)) . "Control";

        $str = $this->tpl($table, $controlName, $data);

        $controlFile = APP_PATH . "/Control/" . $controlName . ".php";

        if (!file_exists($controlFile)) {
            file_put_contents($controlFile, $str);
            $this->log("写入文件 " . $controlFile);
        } else {
            $this->log("文件已经存在 " . $controlFile);
        }

    }

    private function tpl($table, $controlName, $data)
    {

        $rowStr = "";
        foreach ($data as $v) {
            if (in_array($v['Field'], ["id", "status", "created", "updated"])) {
                continue;
            }
            $rowStr .= "        \$row[\"{$v['Field']}\"] = \$this->request->input(\"{$v['Field']}\");\r\n";
        }

        $date = date("Y-m-d H:i:s");
        $tpl  = <<<EOT
<?php
/**
 * @date   $date
 */

namespace App\Control;

class {$controlName} extends RestFullControl
{
    protected \$table;

    public function init()
    {
        \$this->table = "$table";
    }

    /**
    * list data
    */
    function index()
    {
        \$table   = \$this->table;
        \$fields  = "*";
        \$where[] = ["AND `id` = ?", \$this->request->input('id')];
        
        \$page    = \$this->request->input('page', 1);
        \$size    = \$this->request->input('pageSize', 10);

        \$sortField = \$this->request->input('sortField', 'id');
        \$sortOrder = \$this->request->input('sortOrder', 'DESC');
        \$order     = \$sortField . " " . \$sortOrder;

        \$data = \$this->db->getPageData(\$table, \$where, \$page, \$size, \$order, \$fields);
        return \$data;
    }

    function show(\$id)
    {
        \$fields   = "*";
        \$table    = \$this->table;
        \$where[]  = ['AND id = ?', \$id, true];
        \$sqlWhere = \$this->db->where(\$where);
        return \$this->db->getLine('SELECT ' . \$fields . ' FROM `' . \$table . '` WHERE ' . \$sqlWhere);
    }

    function store()
    {
        \$table = \$this->table;
        
$rowStr

        return \$this->db->insert(\$table, \$row);
    }

    function update(\$id)
    {
        \$table = \$this->table;
        
$rowStr

        return \$this->db->update(\$table, \$row, ['id' => \$id]);
    }

    function destroy(\$id)
    {
        \$table = \$this->table;
        return \$this->db->delete(\$table, ['id' => \$id]);
    }

}


EOT;

        return $tpl;
    }

}
