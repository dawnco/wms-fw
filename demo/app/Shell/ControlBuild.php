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

    /**
     * 　　* 下划线转驼峰
     * 　　* 思路:
     * 　　* step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
     * 　　* step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
     * 　　*/
    public function camelize($uncamelized_words, $separator = '_')
    {
        $uncamelized_words = $separator . str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator);
    }

    /**
     * 驼峰命名转下划线命名
     * 思路:
     * 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
     */
    public function uncamelize($camelCaps, $separator = '_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }

    protected function handle($param = null)
    {
        $table = arr_get($param, 0);

        if (!$table) {
            return $this->log('没有指定table');
        }

        $db   = Db::instance();
        $data = $db->getData("desc `$table`");

        $controlName = ucfirst($this->camelize(strtolower($table))) . "Control";

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
    * 列表
    */
    public function index()
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

    /**
    * 显示数据
    */
    public function show(\$id)
    {
        \$fields   = "*";
        \$table    = \$this->table;
        \$where[]  = ['AND id = ?', \$id, true];
        \$sqlWhere = \$this->db->where(\$where);
        return \$this->db->getLine("SELECT \$fields FROM `\$table` WHERE " . \$sqlWhere);
    }

    /**
    * 保存
    */
    public function store()
    {
        \$table = \$this->table;
        
$rowStr

        return \$this->db->insert(\$table, \$row);
    }

    /**
    * 修改
    */
    public function update(\$id)
    {
        \$table = \$this->table;
        
$rowStr

        return \$this->db->update(\$table, \$row, ['id' => \$id]);
    }

    /**
    * 删除
    */
    public function destroy(\$id)
    {
        \$table = \$this->table;
        return \$this->db->delete(\$table, ['id' => \$id]);
    }

}


EOT;

        return $tpl;
    }

}
