<?php

class User
{
    public $id;
    public $phone;
    public $name;


    public static function getAll()
    {
        $users = [];
        $pdo = getPDO();

        $statement = $pdo->prepare('SELECT * FROM users');
        $statement->execute();

        while ($user = $statement->fetchObject()) {
            $users[] = $user;
        }
        return $users;
    }

    public static function getUserByID($id)
    {
        $pdo = getPDO();

        $statement = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $statement->execute([$id]);
        $user = $statement->fetchObject('User');

        $pdo = null;

        return $user;
    }
    public static function getUserByPhone($phone)
    {
        $pdo = getPDO();

        $statement = $pdo->prepare('SELECT * FROM users WHERE phone = ?');
        $statement->execute([$phone]);
        $user = $statement->fetchObject('User');

        $pdo = null;

        return $user;
    }

    public static function create($phone, $name, $operator_id)
    {
        $pdo = getPDO();
        $statement = $pdo
            ->prepare('INSERT INTO users (phone, name, operator_id) 
                                VALUES (?,?,?)'
                     );
        $statement->execute([$phone, $name, $operator_id]);
        $id = $pdo->lastInsertId();
        $user = User::getUserByID($id);
        $pdo = null;
        return $user;
    }



}