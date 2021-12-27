<?php

namespace AegisFang\Migrations\Table;

abstract class Blueprint
{
    public $action = [
        'action' => null,
        'table' => null,
    ];

    /**
     * @var null|string
     */
    public $engine = null;

    /**
     * @var null|string
     */
    public $charset = null;

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
    public $updateColumns = [];

    /**
     * @var array
     */
    public $dropColums = [];

    /**
     * @var array
     */
    public $relationships = [];


    /**
     * @param string $table
     *
     * @return Blueprint
     */
    public static function create(string $table): Blueprint
    {
        return self::newStaticBlueprint($table, 'create');
    }

    /**
     * @param string $table
     *
     * @return Blueprint
     */
    public static function update(string $table): Blueprint
    {
        return self::newStaticBlueprint($table, 'update');
    }

    /**
     * @param string $table
     *
     * @return static
     */
    public static function drop(string $table): Blueprint
    {
        return self::newStaticBlueprint($table, 'drop');
    }

    /**
     * @param $table
     * @param $action
     *
     * @return static
     */
    protected static function newStaticBlueprint($table, $action): Blueprint
    {
        $blueprint = new static();

        $blueprint->action = self::blueprintAction($table, $action);

        return $blueprint;
    }

    /**
     * @param string $table
     * @param string $action
     *
     * @return string[]
     */
    protected static function blueprintAction(string $table, string $action): array
    {
        return [
            'action' => $action,
            'table' => $table,
        ];
    }

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
     * @param Blueprint $blueprint
     *
     * @return $this
     */
    abstract public function change(Blueprint $blueprint): self;

    /**
     * @param string $column
     *
     * @return $this
     */
    abstract public function dropColumn(string $column): self;

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
     * @param string $column
     *
     * @return $this
     */
    public function references(string $column): self
    {
        // Set foreign key on last registered column, referencing $column.
        end($this->columns);

        $key = key($this->columns);

        $this->relationships[] = [
            $key,
            $column,
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
     * @param string $action
     *
     * @return $this
     */
    abstract public function onUpdate(string $action): self;

    /**
     * @param string $action
     *
     * @return $this
     */
    abstract public function onDelete(string $action): self;

    /**
     * @param string $engine
     *
     * @return $this
     */
    public function setEngine(string $engine): self
    {
        $this->engine = $engine;

        return $this;
    }

    /**
     * @param string $charset
     *
     * @return $this
     */
    public function setCharset(string $charset): self
    {
        $this->charset = $charset;

        return $this;
    }
}
