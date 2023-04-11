<?php

namespace core;

interface ICurdData
{
    public static function find($id);
    public static function findAll();
    public static function save($object);
    public static function update($id, $object);
    public static function delete($id);
    public static function where(string $whereClause, array $parameters);
}