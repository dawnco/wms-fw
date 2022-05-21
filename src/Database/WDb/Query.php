<?php
/**
 * @author Dawnco
 * @date   2022-05-21
 */

namespace Wms\Database\WDb;

class Query
{

    public function __construct(string $query)
    {
        $this->query = $query;
    }


    public function where(array $where)
    {
        $build = QueryBuilder::where($where);
        $this->query .= $build['query'];
        $this->params = $build['params'];
    }

    public string $query;
    public array $params;
}
