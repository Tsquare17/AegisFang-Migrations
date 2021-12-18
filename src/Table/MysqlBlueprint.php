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
     * @param $id
     *
     * @return $this
     */
    public function references($id): Blueprint
    {
        // Set foreign key on last registered column, referencing $id.
        end($this->columns);

        $key = key($this->columns);

        $this->relationships[] = [
            $key,
            $id,
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
        end($this->relationships);

        $key = key($this->relationships);

        $this->relationships[$key][] = $table;

        reset($this->relationships);

        return $this;
    }

    /**
     * @param $id
     *
     * @return $this
     */
    public function onUpdate($id): Blueprint
    {
        return $this;
    }

    /**
     * @param $action
     *
     * @return $this
     */
    public function onDelete($action): Blueprint
    {
        return $this;
    }
}
