<?php

namespace AegisFang\Migrations\Table;

abstract class Blueprint
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var array
     */
    public $columns = [];

    /**
     * @var array
     */
    public $relationships = [];


    /**
     * @param string $id
     *
     * @return $this
     */
    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $column
     * @param int $length
     *
     * @return $this
     */
    abstract public function string(string $column, int $length = 255): self;

    /**
     * @param string $column
     * @param int $length
     *
     * @return $this
     */
    abstract public function text(string $column, int $length = 65535): self;

    /**
     * @return $this
     */
    abstract public function unique(): self;

    /**
     * @param $column
     * @param int|null $length
     * @param bool $unsigned
     * @param bool $notNull
     * @param bool $autoincrement
     *
     * @return $this
     */
    public function tinyint(
        $column,
        ?int $length = null,
        bool $unsigned = false,
        bool $notNull = false,
        bool $autoincrement = false
    ): self {
        return $this->intType('TINYINT', $column, $length, $unsigned, $notNull, $autoincrement);
    }

    /**
     * @param $column
     * @param int|null $length
     * @param bool $unsigned
     * @param bool $notNull
     * @param bool $autoincrement
     *
     * @return $this
     */
    public function int(
        $column,
        ?int $length = null,
        bool $unsigned = false,
        bool $notNull = false,
        bool $autoincrement = false
    ): self {
        return $this->intType('INT', $column, $length, $unsigned, $notNull, $autoincrement);
    }

    /**
     * @param $column
     * @param int|null $length
     * @param bool $unsigned
     * @param bool $notNull
     * @param bool $autoincrement
     *
     * @return $this
     */
    public function bigint(
        $column,
        ?int $length = null,
        bool $unsigned = false,
        bool $notNull = false,
        bool $autoincrement = false
    ): self {
        return $this->intType('BIGINT', $column, $length, $unsigned, $notNull, $autoincrement);
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
    abstract public function intType(
        string $type,
        string $column,
        ?int $length,
        bool $unsigned,
        bool $notNull,
        bool $autoincrement
    ): self;

    /**
     * @param $id
     *
     * @return $this
     */
    public function references($id): self
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
    public function on(string $table): self
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
    abstract public function onUpdate($id): self;

    /**
     * @param $action
     *
     * @return $this
     */
    abstract public function onDelete($action): self;
}
