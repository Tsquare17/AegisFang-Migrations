<?php

namespace AegisFang\Migrations\Table;

class MysqlBlueprint extends Blueprint
{
    /**
     * @param string $column
     * @param int $length
     *
     * @return $this
     */
    public function string(string $column, int $length = 255): Blueprint
    {
        $this->columns[$column] = ["VARCHAR({$length})"];

        return $this;
    }

    /**
     * @param string $column
     * @param int $length
     *
     * @return $this
     */
    public function text(string $column, int $length = 65535): Blueprint
    {
        $this->columns[$column] = ["TEXT({$length})"];

        return $this;
    }

    /**
     * @param Blueprint $blueprint
     *
     * @return Blueprint
     */
    public function change(Blueprint $blueprint): Blueprint
    {
        end($this->columns);

        $key = key($this->columns);

        $blueprintKey = key($blueprint->columns);

        if ($key !== $blueprintKey) {
            $this->columns['CHANGE ' . $key . ' ' . $blueprintKey] = [$blueprint->columns[$blueprintKey][0]];
        } else {
            $this->columns['MODIFY ' . $key] = [$blueprint->columns[$blueprintKey][0]];
        }

        unset($this->columns[$key]);

        reset($this->columns);

        return $this;
    }

    /**
     * @param string $column
     *
     * @return Blueprint
     */
    public function dropColumn(string $column): Blueprint
    {
        $key = 'DROP COLUMN ' . $column;

        $this->columns[$key] = [''];

        return $this;
    }

    /**
     * @return $this
     */
    public function unique(): Blueprint
    {
        end($this->columns);

        $key = key($this->columns);

        $this->columns[$key] = [$this->columns[$key][0] . ' UNIQUE'];

        reset($this->columns);

        return $this;
    }

    /**
     * @param string $type
     * @param string $column
     * @param int|null $length
     * @param bool $unsigned
     * @param bool $notNull
     * @param bool $autoincrement
     *
     * @return $this
     */
    public function intType(
        string $type,
        string $column,
        ?int $length,
        bool $unsigned,
        bool $notNull,
        bool $autoincrement
    ): Blueprint {
        $value = $type;
        $value = $length ? $value . '(' . $length . ')' : $value;
        $value = $unsigned ? $value . ' UNSIGNED' : $value;
        $value = $notNull ? $value . ' NOT NULL' : $value;
        $value = $autoincrement ? $value . ' AUTO_INCREMENT' : $value;

        $this->columns[$column] = [$value];

        return $this;
    }

    /**
     * @param string $column
     *
     * @return $this
     */
    public function references(string $column): Blueprint
    {
        end($this->columns);

        $key = key($this->columns);

        $this->relationships[] = [
            'column' => $key,
            'referencedColumn' => $column,
        ];

        reset($this->columns);

        return $this;
    }

    /**
     * @param string $table
     *
     * @return $this
     */
    public function on(string $table): Blueprint
    {
        $this->setOnLastArrayKey($this->relationships, 'referencedTable', $table);

        return $this;
    }

    /**
     * @param string $action
     *
     * @return $this
     */
    public function onUpdate(string $action): Blueprint
    {
        $this->setOnLastArrayKey($this->relationships, 'update', $action);

        return $this;
    }

    /**
     * @param string $action
     *
     * @return $this
     */
    public function onDelete(string $action): Blueprint
    {
        $this->setOnLastArrayKey($this->relationships, 'delete', $action);

        return $this;
    }

    /**
     * @param array $array
     * @param string $key
     * @param $value
     *
     * @return void
     */
    protected function setOnLastArrayKey(array &$array, string $key, $value): void
    {
        end($array);

        $arrayKey = key($array);

        $array[$arrayKey][$key] = $value;

        reset($array);
    }
}
