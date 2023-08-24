<?php

namespace core\db;

use core\model\Model;

class Db
{

    protected \PDO $pdo;
    protected string $table = '';
    protected string $where = '';
    protected array $bindValue = [];
    protected array $joinBindValue = [];
    protected int|string $limit;
    protected string $orderBy;
    protected string $groupBy = '';
    protected string $field = "*";
    protected string $join = '';
    protected string $alias = '';
    protected array $hasOne = [];
    protected array $hasMany = [];
    protected string $relName = '';
    protected string $relFields = "";
    protected array|string $relWith = [];
    protected mixed $relClosure = null;

    public function __construct($key = 'default')
    {
        $this->pdo = PdoConnect::getInstance($key);
    }

    public function dd()
    {
        dd($this->getSql(), $this->bindValue);
    }

    //别名
    public function alias(string $name)
    {
        $this->alias = $name;
        return $this;
    }


    // 開啓事務
    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    // 提交事務
    public function commit(): void
    {
        $this->pdo->commit();
    }

    // 回滾事務
    public function rollBack(): void
    {
        $this->pdo->rollBack();
    }

    //設置表名
    public function table(string $tableName)
    {
        $this->table = "`{$tableName}`";

        return $this;
    }

    //设置字段
    public function field(string $field)
    {
        $this->field = $field;
        return $this;
    }

    //條件
    public function where(string $where, array $bindValue = [])
    {
        if (empty($this->where)) {
            $this->where = " where {$where}";
        } else {
            $this->where .= " and $where";
        }

        $__bindValue = [];
        $__temp = [];
        foreach ($bindValue as $value) {
            if (is_array($value)) {
                $__temp[] = count($value);
                $__bindValue = [...$__bindValue, ...$value];
            } else {
                $__bindValue[] = $value;
            }
        }
        if ($__temp) {
            foreach ($__temp as $count) {
                $strCount = substr(str_repeat("?,", $count), 0, -1);
                $this->where = preg_replace("/in\s*\((\?)\)/", "in(" . $strCount . ")", $this->where, 1);
            }
        }

        $this->bindValue = [...$this->bindValue, ...$__bindValue];

        return $this;
    }

    //limit
    public function limit(string|int $limit)
    {
        $this->limit = $limit;

        return $this;
    }

    //分组
    public function groupBy(string $field)
    {
        $this->groupBy = "group by `{$field}`";

        return $this;
    }

    //排序
    public function orderBy(string $orderBy)
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    //查詢一條
    public function find()
    {
        $sql = $this->getSql();
        $sql .= " limit 1";

        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute($this->bindValue);
        $this->clearEmptyField();
        if (!$result) {
            return [];
        }
        $data =  $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$data){
            return [];
        }
        //一對一
        if (!empty($this->hasOne)){
            foreach($this->hasOne as $key => $item){
                $className = $item['className'];
                $model = new $className();

                $field = $item['fields'] ?: "*";
                $model->field($field);

                if ($item['closure']){
                    $item['closure']($model);
                }


                $alias = $model->alias ?: $model->table;
                $res = $model
                    ->where("{$alias}.{$item['rel_key']}=?",[$data[$item['local_key']]])
                    ->with($item['with'])
                    ->find();
                $data[$key] = $res ?? [];
            }
        }

        //一對多
        if(!empty($this->hasMany)){
            foreach($this->hasMany as $key => $item){

                $className = $item['className'];
                $model = new $className();

                $field = $item['fields'] ?: "*";
                $model->field($field);

                if ($item['closure']){
                    $item['closure']($model);
                }

                $alias = $model->alias ?: $model->table;
                $lists = $model
                    ->where("{$alias}.{$item['rel_key']}=?",[$data[$item['local_key']]])
                    ->with($item['with'])
                    ->findAll();
               if ($lists){
                   $newList = [];
                   foreach($lists as $list){
                       $newList[$list[$item['rel_key']]][] = $list;
                   }
                   $data[$key] = array_values($newList);
               }
            }
        }

        return $data;
    }

    //查詢所有
    public function findAll()
    {
        $sql = $this->getSql();

        if (!empty($this->limit)) {
            $sql .= " limit {$this->limit}";
        }

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute($this->bindValue);
        $this->clearEmptyField();
        if (!$result) {
            return [];
        }

        $data = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

            if (!empty($this->hasOne)){
                foreach($this->hasOne as $key => $item){
                    $className = $item['className'];
                    $model = new $className();

                    $field = $item['fields'] ?: "*";
                    $model->field($field);

                    if ($item['closure']){
                        $item['closure']($model);
                    }

                    $alias = $model->alias ?: $model->table;
                    $res = $model
                        ->where("{$alias}.{$item['rel_key']}=?",[$row[$item['local_key']]])
                        ->with($item['with'])
                        ->find();
                    $row[$key] = $res ?? [];
                }
            }
            //一對多
            if (!empty($this->hasMany)){
                foreach($this->hasMany as $key => $item){

                    $className = $item['className'];
                    $model = new $className();

                    $field = $item['fields'] ?: "*";
                    $model->field($field);

                    if ($item['closure']){
                        $item['closure']($model);
                    }

                    $alias = $model->alias ?: $model->table;
                    $lists = $model
                        ->where("{$alias}.{$item['rel_key']}=?",[$row[$item['local_key']]])
                        ->with($item['with'])
                        ->findAll();
                    if ($lists){
                        $newList = [];
                        foreach($lists as $list){
                            $newList[$list[$item['rel_key']]][] = $list;
                        }
                        $row[$key] = array_values($newList);
                    }
                }
            }


            $data[] = $row;
        }

        return $data;
    }

    //更新
    public function update(array $data)
    {
        $fields = array_keys($data);
        $updateStr = implode("=?,", $fields) . "=?";
        $this->bindValue = [...array_values($data), ...$this->bindValue];
        $sql
            = "update {$this->table} set {$updateStr} {$this->where}";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute($this->bindValue);
        $this->clearEmptyField();
        if (!$result) {
            return false;
        }
        return $stmt->rowCount();
    }

    //清空字段
    protected function clearEmptyField(): void
    {
        $this->table = '';
        $this->limit = 0;
        $this->orderBy = '';
        $this->where = '';
        $this->bindValue = [];
        $this->field = "*";
        $this->groupBy = '';
        $this->join = '';
        $this->alias = '';
        $this->joinBindValue = [];
    }

    //執行原生語句
    public function query(string $sql, array $bindValue = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute($bindValue);

        if (!$result) {
            return false;
        }
        $data = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        $this->clearEmptyField();
        return $data;
    }

    // 插入數據
    public function insert(array $data)
    {
        if (empty($data)) {
            return false;
        }

        $fieldArr = array_keys($data);
        $fields = '`' . implode("`,`", $fieldArr) . "`";
        $valueArr = array_values($data);
        $valueStr = str_repeat('?,', count($valueArr));
        $valueStr = substr($valueStr, 0, -1);

        $sql = "insert into {$this->table}({$fields}) values($valueStr)";

        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute($valueArr);
        $this->clearEmptyField();
        if (!$result) {
            return false;
        }

        return $this->pdo->lastInsertId();

    }

    // 批量插入數據
    public function insertAll(array $data)
    {

        if (empty($data) || empty($data[0])) {
            return false;
        }
        $fieldArr = array_keys($data[0]);
        $fields = '`' . implode("`,`", $fieldArr) . "`";


        $valueStr = str_repeat("?,", count($data[0]));
        $valueStr = substr($valueStr, 0, -1);

        $valueStrs = str_repeat("({$valueStr}),", count($data));
        $valueStrs = substr($valueStrs, 0, -1);

        $sql = "insert into {$this->table}({$fields}) values{$valueStrs}";
        $bindValue = [];

        foreach ($data as $item) {
            $bindValue = [...$bindValue, ...array_values($item)];
        }

        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute($bindValue);
        $this->clearEmptyField();
        if (!$result) {
            return false;
        }

        return $stmt->rowCount();

    }

    //刪除
    public function delete()
    {
        if (empty($this->where)) {
            return false;
        }
        $sql = "delete from {$this->table} {$this->where}";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute($this->bindValue);
        $this->clearEmptyField();
        if (!$result) {
            return false;
        }

        return $stmt->rowCount();
    }

    //获取sql
    public function getSql()
    {
        $table = $this->table;
        if ($this->alias) {
            $table .= " as {$this->alias}";
        }

        $sql = "select {$this->field} from {$table} {$this->join} {$this->where} {$this->groupBy}";


        if (!empty($this->orderBy)) {
            $sql .= " order by {$this->orderBy}";
        }

        $this->bindValue = [...$this->joinBindValue, ...$this->bindValue];
        return $sql;
    }

    //内关联
    public function innerJoin(string $table, string $alias, string $where, array $bindValue = [])
    {
        $this->join .= " inner join {$table} as {$alias} on {$where}";
        $this->joinBindValue = [...$this->joinBindValue, ...$bindValue];
        return $this;
    }

    //左关联查询
    public function leftJoin(string $table, string $alias, string $where, array $bindValue = [])
    {
        $this->join .= " left join {$table} as {$alias} on {$where}";
        $this->joinBindValue = [...$this->joinBindValue, ...$bindValue];
        return $this;
    }

    //右关联查询
    public function rightJoin(string $table, string $alias, string $where, array $bindValue = [])
    {
        $this->join .= " right join {$table} as {$alias} on {$where}";
        $this->joinBindValue = [...$this->joinBindValue, ...$bindValue];
        return $this;
    }

    //统计
    public function count()
    {
        $this->field = "count(*) as count";
        $sql = $this->getSql();
        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute($this->bindValue);
        $this->clearEmptyField();
        if (!$result) {
            return [];
        }
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data['count'];
    }

    //获取子查询语句
    public function getQuery(string $sql, array $bindValue = [])
    {
        $this->joinBindValue = [...$this->joinBindValue, ...$bindValue];
        return "({$sql})";

    }


}