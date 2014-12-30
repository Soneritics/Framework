<?php
interface IDatabaseType
{
    public function __construct(array $config);
    public function quote($value);
    public function quoteDatabase($db);
    public function quoteTable($table);
    public function quoteIdentifier($id);
    public function query($query);
    public function buildQuery(QueryAbstract $query);
} 