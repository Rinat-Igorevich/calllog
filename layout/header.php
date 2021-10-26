<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require $_SERVER['DOCUMENT_ROOT'] . '/src/Call.php';
require $_SERVER['DOCUMENT_ROOT'] . '/src/Operator.php';
require $_SERVER['DOCUMENT_ROOT'] . '/src/User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require $_SERVER['DOCUMENT_ROOT'] . '/functions.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'changeCallCost':
            foreach ($_POST as $key => $row){
                if (mb_substr($key,0,11) == 'operator_id') {
                    $id = mb_substr($key,12);
                    Operator::changeCallCost($row, $_POST['operatorToChange'], $id);
                }
            }
            $data = ['result' => $_POST];
            die(json_encode($data));

        case 'getCall':
            $result = Call::getCallByID($_POST['id']);
            $time = new DateTime($result[0]->date);
            $date['date'] = $time->format('Y-m-d');
            $date['time'] = $time->format('H:i');
            $user = User::getUserByID($result[0]->user_id);
            $data = ['result' => $result, 'user' => $user, 'date' => $date];
            die(json_encode($data));

        case 'createCall':
            $result = Call::createCall();
            if (isset($result['error'])) {
                $data = ['error' => $result['error']];
            } else {
                $data = ['result' => $result];
            }
            break;

        case 'checkPhone':
            $result = Call::checkPhone($_POST['userPhone'], $_POST['user'] ?? null);
            if (isset($result['error'])) {
                $data = ['error' => $result['error']];
                die(json_encode($data));
            }
            $data = ['result' => $result];
            die(json_encode($data));

        case 'changeCall':
            $result = Call::change();
            $data = ['result' => $result];
            break;
    }
}

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


    <script src="../scripts.js"></script>

    <title>Тестовое</title>
</head>
<header class="d-flex justify-content-center py-3">

    <a class="navbar-brand" href="/">Журнал</a>
    <a class="navbar-brand" href="layout/operators.php">операторы</a>

</header>
<body>
