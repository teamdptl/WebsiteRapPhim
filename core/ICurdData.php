<?php

namespace core;

interface ICurdData
{
    public static function find(...$ids);
    public static function findAll();
    public static function save($object);
    public static function update($object, ...$ids);
    public static function delete(...$ids);
    public static function where(string $whereClause, array $parameters);
}