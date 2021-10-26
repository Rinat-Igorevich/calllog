let operator = {
    ajaxMethod: 'POST',

    change: function (id) {
        $('#row'+ id +' :input').each(function () {
            console.log($(this).val())
            $(this).prop('disabled', false)
        });

    },

    save: function (id) {
        let formData = new FormData
        let count = 1
        $('#row'+ id +' :input').each(function () {
            if($(this).val() != ''){
                formData.append('operator_id_'+count, $(this).val())
                $(this).prop('disabled', true)
            }
            count++
        })
        formData.append('operatorToChange', id)
        formData.append('action', 'changeCallCost')
        $.ajax({
            url: '/',
            type: this.ajaxMethod,
            data: formData,
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,

            success: function (respond) {
                if (typeof respond.error === 'undefined') {

                    console.log(respond)

                } else {
                    alert(respond.error)
                }
            },
            error: function (jqXHR, status, errorThrown) {
                console.log('ОШИБКА AJAX запроса: ' + status, jqXHR);
            }
        });

    },

    saveToDB: function (id, val) {

        let formData = new FormData();

        formData.append('action', 'changeCallCost')
        formData.append('operator_id', id)
        formData.append('new_cost', val)

        $.ajax({
            url: '/',
            type: this.ajaxMethod,
            data: formData,
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,

            success: function (respond) {
                if (typeof respond.error === 'undefined') {

                    console.log(respond)

                } else {
                    alert(respond.error)
                }
            },
            error: function (jqXHR, status, errorThrown) {
                console.log('ОШИБКА AJAX запроса: ' + status, jqXHR);
            }
        });

    }
};

let call = {

    change: function (id) {
        $('#changeCallModal').modal('show')
        let formData = new FormData
        formData.append('action', 'getCall')
        formData.append('id', id)
        $('#submit').val('changeCall')
        $.ajax({
            url: '/',
            type: 'post',
            data: formData,
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,

            success: function (respond) {
                if (typeof respond.error === 'undefined') {
                    $('#callID').val(respond.result[0]['id'])
                    // $('#callID').prop('disabled', true)
                    $('#user').val(respond.user['name'])
                    $('#userPhone').val(respond.user['phone'])
                    $('#userOperator').val(respond.user['operator_id'])
                    $('#callDate').val(respond.date['date'])
                    $('#callTime').val(respond.date['time'])
                    $('#phoneCallTo').val(respond.result[0]['phone'])
                    $('#toOperator').val(respond.result[0]['operator_id'])
                    $('#callDuration').val(respond.result[0]['duration'])
                    console.log(respond)

                } else {
                    alert(respond.error)
                }
            },
            error: function (jqXHR, status, errorThrown) {
                console.log('ОШИБКА AJAX запроса: ' + status, jqXHR);
            }
        });
    },

    createOrChange: function () {
        let formData = new FormData
        console.log($('#submit').val())
        formData.append('action', $('#submit').val())
        formData.append('callID', $('#callID').val())
        formData.append('user', $('#user').val())
        formData.append('userPhone', $('#userPhone').val())
        formData.append('userOperator', $('#userOperator').val())
        formData.append('callDate', $('#callDate').val())
        formData.append('callTime', $('#callTime').val())
        formData.append('phoneCallTo', $('#phoneCallTo').val())
        formData.append('toOperator', $('#toOperator').val())
        formData.append('callDuration', $('#callDuration').val())

        $.ajax({
            url: '/',
            type: 'post',
            data: formData,
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,

            success: function (respond) {
                if (typeof respond.error === 'undefined') {
                    console.log(respond)
                } else {
                    alert(respond.error)
                }
            },
            error: function (jqXHR, status, errorThrown) {
                console.log('ОШИБКА AJAX запроса: ' + status, jqXHR);
            }
        })
    },

    callFormReset: function () {
        $('#createCall')[0].reset()
    },

    checkUserPhone: function () {
        let formData = new FormData
        $('#userOperator').prop('disabled', false)
        formData.append('action', 'checkPhone')
        formData.append('userPhone', $('#userPhone').val())
        formData.append('user', $('#user').val())

        $.ajax({
            url: '/',
            type: 'post',
            data: formData,
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,

            success: function (respond) {
                if (typeof respond.error === 'undefined') {
                    if (respond.result != null) {
                        $('#userOperator').val(respond.result['operator_id'])
                        $('#userOperator').prop('disabled', true)
                    }
                    $('#submit').prop('disabled', false)
                } else {
                    $('#submit').prop('disabled', true)
                    alert(respond.error)
                }
            },
            error: function (jqXHR, status, errorThrown) {
                console.log('ОШИБКА AJAX запроса: ' + status, jqXHR);
            }
        })
    },

    checkCallToPhone: function () {
        let formData = new FormData
        $('#toOperator').prop('disabled', false)
        formData.append('action', 'checkPhone')
        formData.append('userPhone', $('#phoneCallTo').val())


        $.ajax({
            url: '/',
            type: 'post',
            data: formData,
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,

            success: function (respond) {
                if (typeof respond.error === 'undefined') {
                    if (respond.result != null) {
                        $('#toOperator').val(respond.result['operator_id'])
                        $('#toOperator').prop('disabled', true)
                    }
                    $('#submit').prop('disabled', false)
                } else {

                }
            },
            error: function (jqXHR, status, errorThrown) {
                console.log('ОШИБКА AJAX запроса: ' + status, jqXHR);
            }
        })
    }
}




