<?php

?>

<div class="modal fade" id="changeCallModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="createCall" action="/" method="post">
                <div class="modal-header">
                    <div>
                        <H6 class="modal-title">Запись №</H6>
                        <input id="callID" name="callID" >
                    </div>
                    <div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>

                <div class="modal-body">
                    <label class="">
                        <input id="user" name="user" required style="margin-bottom: 10px" onchange="call.checkUserPhone()">
                    </label> Кто звонил
                    <label class="">
                        <input id="userPhone" name="userPhone" required onchange="call.checkUserPhone()"> Телефон
                    </label>
                    <label class="">
                        <select id="userOperator" name="userOperator" onchange="" required>
                            <option disabled selected>Оператор</option>
                            <?php foreach ($operators as $operator): ?>
                                <option value="<?= $operator->id ?>"><?= $operator->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label> Оператор
                    <hr>
                    <label class="">
                        <input type="date" name="callDate"  id="callDate" required> Дата и время
                        <input type="time" name="callTime" id="callTime" required>
                    </label>
                    <hr>
                    Куда звонил <br>
                    <label class="">
                        <input id="phoneCallTo" name="phoneCallTo" required onchange="call.checkCallToPhone()"> Телефон
                    </label>
                    <label class="">
                        <select id="toOperator" name="toOperator" required>
                            <option selected >Оператор</option>
                            <?php foreach ($operators as $operator): ?>
                                <option value="<?= $operator->id ?>"><?= $operator->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label> Оператор
                    <hr>
                    <input type="time" max="10:00:00" step="1" min="00:00:00" name="callDuration" id="callDuration" required>
                    <label for="startTime">Длительность: ч:мин:сек </label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="call.callFormReset()">
                        Закрыть
                    </button>
                    <button type="submit" id="submit" name="action" value="createCall" form="createCall" class="btn btn-primary" >
                        Сохранить
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>