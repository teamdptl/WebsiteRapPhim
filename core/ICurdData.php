<?php

namespace core;

interface ICurdData
{
    public static function find($option, ...$ids);
    public static function findAll($option);
    public static function save($object);
    public static function update($object, ...$ids);
    public static function delete($softDelete, ...$ids);
    public static function where(string $whereClause, array $parameters);
}