<?php

namespace Creational\Builder;

interface SQLQueryBuilder
{
    public function select(string $table, array $fields): SQLQueryBuilder;

    public function where(string $field, string $value, string $operator = '='): SQLQueryBuilder;
    
    public function limit(int $start, int $offset): SQLQueryBuilder;
    
    public function getSQL(): string;
}

class MysqlQueryBuilder implements SQLQueryBuilder
{
    protected $query;

    protected function reset(): void
    {
        $this->query = new \stdClass;
    }

    public function select(string $table, array $fields): SQLQueryBuilder
    {
        $this->reset();
        $this->query->base = "SELECT " . implode(", ", $fields) . " FORM " . $table;
        $this->query->type = "select";
        
        return $this;
    }

    public function where(string $field, string $value, string $operator = '='): SQLQueryBuilder
    {
        if(!in_array($this->query->type, ['select', 'update'])) {
            throw new \Exception("Where can only be added to SELECT or UPDATE");
        }
        $this->query->where[] = "$field $operator '$value'";
        return $this;
    }

    public function limit(int $start, int $offset): SQLQueryBuilder
    {
        if(!in_array($this->query->type, ["select"])) {
            throw new \Exception("LIMIT can only be added to SELECT");
        }
        $this->query->limit = " LIMIT " . $start . ", " . $offset;
        return $this;
    }

    public function getSQL()
    {
        $query = $this->query;
        $sql = $query->base;
        if(!empty($query->where)) {
            $sql .= " WHERE " . implode(" AND ", $query->where);
        }
        if(isset($query->limit)) {
            $sql .= $query->limit;
        }
        $sql .= ";";
        return $sql;
    }
}

class PostgresQueryBuilder implements SQLQueryBuilder
{
    public function limit(int $start, int $offset): SQLQueryBuilder
    {
        parent::limit($start, $offset);
        $this->query->limit = " LIMIT " . $start . " OFFSET " . $offset;
        return $this;
    }
}

function clientCode(SQLQueryBuilder $queryBuilder)
{
    $query = $queryBuilder->select("users", ["name", "email", "password"])->where("age", "18", ">")->where("age", "30", "<")->limit(10, 20)->getSQL();
    
    echo $query;
}

echo "Testing MYSQL query builder:";
clientCode(new MysqlQueryBuilder);

echo "Testing MYSQL query builder:";
clientCode(new PostgresQueryBuilder);