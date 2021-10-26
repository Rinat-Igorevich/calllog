<?php

class Operator
{
    public $id;
    public $name;

    public static function getAll()
    {
        $pdo = getPDO();
        $statement = $pdo->prepare("SELECT * FROM operators");
        $statement->execute();

        $operators = $statement->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Operator');

        $pdo = null;

        return $operators;
    }

    public static function getOperatorByID($id)
    {
        $pdo = getPDO();
        $statement = $pdo->prepare("SELECT * FROM operators WHERE id=?");
        $statement->execute([$id]);

        $operator = $statement->fetchObject();

        $pdo = null;

        return $operator;
    }

    public static function getOperatorByName($name)
    {
        $pdo = getPDO();
        $statement = $pdo->prepare("SELECT * FROM operators WHERE name=?");
        $statement->execute([$name]);

        $operator = $statement->fetchObject();

        $pdo = null;

        return $operator;
    }

    public static function getMinuteCost($operatorFrom, $operatorTo)
    {
        $pdo = getPDO();
        $statement = $pdo->prepare('SELECT minute_cost 
                                            FROM operators_cost 
                                          WHERE from_operator_id=? AND to_operator_id=?');
        $statement->execute([$operatorFrom, $operatorTo]);
        $result = $statement->fetchColumn();
        $pdo = null;

        return $result;
    }

    public static function changeCallCost($cost, $from, $to)
    {
        $pdo = getPDO();
        $statement = $pdo->prepare('UPDATE operators_cost SET minute_cost=? 
                                        WHERE from_operator_id=? AND to_operator_id=?');
        $statement->execute([$cost, $from, $to]);

    }

}