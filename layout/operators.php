<?php
require 'header.php';

$operators = Operator::getAll();

?>

<div class="container">

    <table class="table caption-top">
        <caption>Операторы</caption>
        <thead>
        <tr>

            <th scope="col"></th>
        <?php foreach ($operators as $operator): ?>
            <th scope="col"><?= $operator->name ?></th>

        <?php endforeach; ?>
            <th scope="col">Изменить</th>
            <th scope="col">Сохранить</th>
        </tr>
        </thead>
        <tbody >
        <?php foreach ($operators as $operatorRow): ?>
          <tr id="row<?=$operatorRow->id?>">
          <th><?= $operatorRow->name ?></th>
            <?php foreach ($operators as $operatorCol): ?>
                <td>
                    <label for="cost<?= $operatorRow->id .'-' . $operatorCol->id ?>">
                    <input value="<?=Operator::getMinuteCost($operatorRow->id, $operatorCol->id)?>" id="cost<?= $operatorRow->id .'-' . $operatorCol->id ?>" disabled>
                    </label>
                </td>
            <?php endforeach; ?>
              <td>
                  <button class="btn" onclick="operator.change(<?= $operatorRow->id?>)">редактировать</button>
              </td>
              <td>
                  <button class="btn" id="save<?= $operatorRow->id?>" onclick="operator.save(<?= $operatorRow->id?>)" disabled>сохранить</button>
              </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>
<?php

require 'footer.php';