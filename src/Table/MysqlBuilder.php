<?php

namespace AegisFang\Migrations\Table;

use AegisFang\Migrations\Driver\AdapterInterface;

class MysqlBuilder extends Builder
{
    protected const CREATETABLE = 'CREATE TABLE IF NOT EXISTS';
    protected const DROPTABLE = 'DROP TABLE';
    protected const PRIMARYKEY = 'INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT';


    /**
     * @return bool
     */
    public function createTable(): bool
    {
        if ($this->tableExists()) {
            return false;
        }

        $this->statement = self::CREATETABLE . " `{$this->table}` (";
        $this->setColumns();
        $this->closeTable();

        return $this->execute();
    }

    /**
     * Create foreign key relationships.
     */
    public function createRelationships(): void
    {
        $i = 0;
        foreach ($this->relationships as $relationship) {
            $this->statement = 'ALTER TABLE ' . $relationship['referencedTable'] . ' ADD';
            $this->statement .= ' KEY ' . $relationship['column'] . '_' . $relationship['referencedColumn'] . '_' . $i .
                '(' . $relationship['column'] . ');';

            $this->execute();

            $i++;
        }

        foreach ($this->relationships as $relationship) {
            $this->statement = 'ALTER TABLE ' . $this->table . ' ADD FOREIGN KEY(' . $relationship['column']
                . ') REFERENCES ' . $relationship['referencedTable'] . '(' . $relationship['referencedColumn'] . ')';

            if (isset($relationship['update'])) {
                $this->statement .= " ON UPDATE {$relationship['update']}";
            }

            if (isset($relationship['delete'])) {
                $this->statement .= " ON DELETE {$relationship['delete']}";
            }

            $this->execute();
        }
    }

    /**
     * @return void
     */
    public function setColumns(): void
    {
        $i = 0;
        $len = count($this->columns);
        $this->statement .= "{$this->id} " . self::PRIMARYKEY . ', ';
        foreach ($this->columns as $column => $options) {
            $this->statement .= "{$column} ";
            foreach ($options as $option) {
                $this->statement .= " {$option}";
            }

            if ($i !== $len - 1) {
                $this->statement .= ', ';
            }
            $i++;
        }
    }

    /**
     * @return void
     */
    public function closeTable(): void
    {
        $this->statement .= ')';
    }

    /**
     * @param AdapterInterface $connectionAdapter
     * @param string $table
     *
     * @return bool
     */
    public static function destroy(AdapterInterface $connectionAdapter, string $table): bool
    {
        $drop = new self($connectionAdapter, $table, new MysqlBlueprint());
        $drop->statement(self::DROPTABLE . " {$table}");

        return $drop->execute();
    }
}
