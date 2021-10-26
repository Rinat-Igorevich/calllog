<?php

class Call
{
    public $id;
    public $user_id;
    public $phone;
    public $date;
    public $duration;
    public $operator_id;
    public $cost;

    public static function getAll()
    {
        $pdo = getPDO();

        $statement = $pdo->prepare('SELECT * FROM calls');
        $statement->execute();

        $calls = $statement->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Call');
        $pdo = null;
        return $calls;
    }

    public function getCallCost()
    {
        $minuteCost = $this->getMinuteCost();
        $minutes = $this->countMinutes($this->duration);

        return $minuteCost * $minutes;
    }

    public function countMinutes($time)
    {
        $seconds = abs(strtotime($time) - strtotime('00:00:00'));
        return ceil($seconds / 60);
    }

    public static function getTotalCost($calls)
    {
        $totalCost = 0;

        foreach ($calls as $call) {
            $totalCost += $call->cost;
        }
        return $totalCost;
    }

    public static function getTotalTime($calls)
    {
        $hours = 0;
        $minutes = 0;
        $seconds = 0;

        foreach ($calls as $call) {
            $split = explode(':', $call-> duration);

            $hours += $split[0];
            $minutes += $split[1];
            $seconds += $split[2];

        }
        $minutes += floor($seconds/60);
        $hours += floor($minutes/60);
        $minutes = $minutes%60;
        $seconds = $seconds%60;

        if (strlen($seconds) == 1) {
            $seconds = '0' . $seconds;
        }
        if (strlen($minutes) == 1) {
            $minutes = '0' . $minutes;
        }
        if (strlen($hours) == 1) {
            $hours = '0' . $hours;
        }

        $time = $hours . ':' . $minutes . ':' . $seconds;

        return $time;
    }

    public function getMinuteCost()
    {
        $userOperator = User::getUserByID($this->user_id)->operator_id;

        return Operator::getMinuteCost($userOperator, $this->operator_id);
    }

    public static function getCallByID($id)
    {
        $pdo = getPDO();

        $statement = $pdo->prepare('SELECT * FROM calls WHERE id = ?');
        $statement->execute([$id]);

        $call = $statement->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Call');
        $pdo = null;
        return $call;
    }

    public static function createCall()
    {
        $pdo = getPDO();
        $user = User::getUserByPhone($_POST['userPhone']);
        $callTo = User::getUserByPhone($_POST['phoneCallTo']);
        $error = '';
        $dateTime = $_POST['callDate'] . ' ' . $_POST['callTime'];

        //если пользователь найден в базе по телефону, но от фронта пришло др имя
        if ($user && $user->name != $_POST['user']) {
           return ['error' => 'Данный номер закреплен за другим пользователем'];
        } else if (!$user) {
            $user = User::create($_POST['userPhone'], $_POST['user'], $_POST['userOperator']);
        }
        if ($callTo) {
            $toOperator = $callTo->operator_id;
        } else {
            $toOperator = $_POST['toOperator'];
        }

        $query = 'INSERT INTO calls(user_id, phone, date, duration, operator_id) 
                    VALUES (?, ?, ?, ?, ?)';

        $statement = $pdo->prepare($query);
        $statement->execute([$user->id, $_POST['phoneCallTo'], $dateTime, $_POST['callDuration'], $toOperator]);
        Call::setCost($pdo->lastInsertId());
        $pdo = null;
        return $statement->errorCode();
    }

    private static function setCost($id)
    {
        $call = self::getCallByID($id);
        $cost = $call[0]->getCallCost();

        $pdo = getPDO();
        $statement = $pdo->prepare('UPDATE calls SET cost = ?
                                           WHERE id = ?
                                  ');
        $statement->execute([floatval($cost), $id]);
        $pdo = null;

        return $statement;
    }

    public static function checkPhone($phone, $userName)
    {
        $user = User::getUserByPhone($_POST['userPhone']);

        if ($user && isset($_POST['user']) &&  $user->name != $_POST['user']) {
            return ['error' => 'Данный номер закреплен за другим пользователем'];
        } elseif ($user) {
            return ['operator_id'=> $user->operator_id];
        }
    }

    public static function change()
    {
        $user = User::getUserByPhone($_POST['userPhone']);
        if (!$user) {
            $user = User::create($_POST['userPhone'], $_POST['user'], $_POST['userOperator']);
        }
        $dateTime = $_POST['callDate'] . ' ' . $_POST['callTime'];
        $pdo = getPDO();
        $query = 'UPDATE calls SET user_id=?, phone=?, date=?, duration=?, operator_id=?
                    WHERE id=?';
        $statement = $pdo->prepare($query);
        $statement->execute([$user->id, $user->phone, $dateTime, $_POST['callDuration'], $_POST['toOperator'], $_POST['callID']]);
        Call::setCost($_POST['callID']);
    }

    public static function getCallsWithFilter()
    {
        $pdo = getPDO();
        $keyWord= ' WHERE ';
        $args=[];

        $query = 'SELECT * FROM calls';

        if (isset($_GET['userID'])) {
            $query .= $keyWord . 'user_id=?';
            array_push($args, $_GET['userID']);
            $keyWord = ' AND ';
        }
        if (isset($_GET['filterOperatorId'])) {
            $query .= $keyWord . 'operator_id=?';
            array_push($args, $_GET['filterOperatorId']);
            $keyWord = ' AND ';
        }
        if (isset($_GET['filterDateFrom']) && $_GET['filterDateFrom'] != '') {
            $query .= $keyWord . 'date > ? ';
            array_push($args, $_GET['filterDateFrom'] . ' 00:00:00');

            $keyWord = ' AND ';
        }
        if (isset($_GET['filterDateTo']) && $_GET['filterDateTo'] != '') {
            $query .= $keyWord . 'date < ? ';
            array_push($args, $_GET['filterDateTo'] . ' 23:59:59');

            $keyWord = ' AND ';
        }

        $statement = $pdo->prepare($query);
        $statement->execute($args);

        $calls = $statement->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Call');
        $pdo = null;
        return $calls;
    }

}