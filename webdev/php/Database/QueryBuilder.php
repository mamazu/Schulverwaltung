<?php

class QueryBuilder
{
    private array $selectFields = [];
    private array $joins = [];
    private array $conditions = [];
    private array $bindings = [];
    private string $fromTable;

    public function __construct(string $fromTable, array $fields = [])
    {
        $this->fromTable = $fromTable;
        $this->addSelectFields($fields);
    }

    public function getSQL(): string
    {
        $fields = '*';
        if (count($this->selectFields)) {
            $fields = '';
            foreach ($this->selectFields as $defintion => $alias) {
                $fields .= "\t" . $defintion . ' AS ' . $alias . ",\n";
            }
            $fields = substr($fields, 0, strlen($fields) - 2);
        }

        // Handle conditions
        $conditions = '';
        if (count($this->conditions) > 0) {
            $conditions = 'WHERE ' . join(' AND ', $this->conditions);
        }

        // Handle joins
        $joins = '';
        foreach ($this->joins as $join) {
            [$table, $joinCondition, $type] = $join;
            $joins .= sprintf('%s JOIN %s ON %s', $type, $table, $joinCondition);
            $joins .= "\n";
        }

        $sql = <<<SQL
SELECT
%s 
FROM `%s` 
%s
%s
SQL;
        return sprintf($sql, $fields, $this->fromTable, $joins, $conditions);
    }

    public function addSelectFields(array $fieldDefinitions): void
    {
        $this->selectFields = array_merge($this->selectFields, $fieldDefinitions);
    }

    public function addSelectField(string $definition, ?string $alias = null)
    {
        $this->selectFields[$definition] = $alias ?? $definition;
    }

    public function andWhere(string $condition, array $bindings = [])
    {
        $this->conditions[] = $condition;
        foreach ($bindings as $binding) {
            $this->bindings[] = $binding;
        }
    }

    /**
     * @param string $joinTable
     *      Table that should be joined. This can be a two part string if you want to alias the table
     *      eg. addJoin('tableA A', 'A.test = B.test')
     * @param string $joinCondition
     * @param string $joinType
     *      INNER, LEFT, RIGHT
     */
    public function addJoin(string $joinTable, string $joinCondition, string $joinType = '')
    {
        $this->joins[] = [$joinTable, $joinCondition, $joinType];
    }

    public function execute(mysqli $database): mysqli_result
    {
        $statement = $database->prepare($this->getSQL());
        if (count($this->bindings) > 0) {
            $bindingTypes = '';
            foreach ($this->bindings as &$binding) {
                if (is_int($binding) | is_bool($bindingTypes)) {
                    $bindingTypes .= 'i';
                    $binding = (int)$binding;
                } else if (is_string($binding)) {
                    $bindingTypes .= 's';
                } else if (is_double($binding) || is_float($binding)) {
                    $bindingTypes .= 'd';
                } else {
                    throw new InvalidArgumentException('Unkown type of variable');
                }

            }

            $statement->bind_param($bindingTypes, ...$this->bindings);
        }
        $statement->execute();

        return $statement->get_result();
    }
}