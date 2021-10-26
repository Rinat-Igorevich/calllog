<?php

require 'layout/header.php';
if (!isset($_GET['action'])) {
    $calls = Call::getAll();
} else {
    $calls = Call::getCallsWithFilter();
}
$users = User::getAll();
$operators = Operator::getAll();


?>

<div class="container">
    <hr>
    <form id="filter" class="container" action="/" method="get">
        <div>Фильтры</div>
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label class="form-label" for="filterUser"></label>
                <select id="filterUser" name="userID">
                    <option disabled selected>Пользователь</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user->id ?>"><?= $user->name ?></option>
                    <?php endforeach; ?>
                </select>

            </div>
            <div class="col-auto">
            <label>С даты
                <input type="date" name="filterDateFrom">
            </label>
            </div>
            <div class="col-auto">
            <label>По дату
                <input type="date" name="filterDateTo">
            </label>
            </div>
            <div class="col-auto">
            <label>
                <select name="filterOperatorId">
                    <option disabled selected>Оператор</option>
                    <?php foreach ($operators as $operator): ?>
                        <option value="<?= $operator->id ?>"><?= $operator->name ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            </div>
            <div class="col-auto">
            <button class="btn btn-primary" name="action" value="filter" type="submit">Фильтровать</button>
            </div>
            <div class="col-auto">
            <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#changeCallModal">Добавить запись</button>
            </div>
        </div>
    </form>
    <hr>
    <table class="table caption-top">
        <caption>Журнал звонков</caption>
        <thead>
        <tr>
            <th scope="col">№</th>
            <th scope="col">Пользователь</th>
            <th scope="col">Номер</th>
            <th scope="col">Дата</th>
            <th scope="col">Длительность</th>
            <th scope="col">Оператор</th>
            <th scope="col">Цена руб./мин.</th>
            <th scope="col">Стоимость</th>
            <th scope="col">Редактировать</th>
        </tr>
        </thead>
        <tbody class="page-products__list">
        <?php foreach ($calls as $call): ?>
            <tr>
                <th scope="row"><?= $call->id ?></th>
                <td><?= User::getUserByID($call->user_id)->name ?></td>
                <td><?= $call->phone ?></td>
                <td><?= $call->date ?></td>
                <td><?= $call->duration ?></td>
                <td><?= Operator::getOperatorByID($call->operator_id)->name ?></td>
                <td><?= $call->getMinuteCost() ?></td>
                <td><?= $call->getCallCost() ?></td>
                <td><button class="button btn-dark" id="<?= $call->id ?>" onclick="call.change(<?= $call->id ?>)">🖉</button></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="4" style="text-align:right">Длительность всего:</td>
            <td><?= Call::getTotalTime($calls) ?></td>
            <td colspan="2" style="text-align:right">Сумма:</td>
            <td><?= Call::getTotalCost($calls) ?></td>

        </tr>
        </tbody>
    </table>

</div>
<?php
require 'layout/calllog/popup.php';
