$('#submit-btn-csv-form').click( function() {
    $('#submit-btn-csv-form').attr('disabled', 'disabled')
    var data = new FormData();
    if ($('input[type=file]')[0].files[0]) {
        data.append('document', $('input[type=file]')[0].files[0]);
        jQuery.ajax({
            url: '/checkCsv',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            success: function(data){
                data = JSON.parse(data)
                console.log(data)
                if (data.error) {
                    $('#response-message-for-csv').text(data.message)
                    $('#response-message-for-csv').show()
                } else {
                    $('.names').text(data.names)
                    $('.average-percent').text(data.averageScore)
                }
                $('#submit-btn-csv-form').attr('disabled', false)
            }
        });
    } else {
        $('#response-message-for-csv').text('File is required')
        $('#response-message-for-csv').show()
    }
});


$('#submit-btn-percent-form').click(function () {
    $.post('/percent', $('#form-percent').serialize()).then(function (data){
        data = JSON.parse(data)
        if(data.error) {
            $('#response-message-for-percent').text(data.message)
            $( "#response-message-for-percent" ).addClass('invalid-feedback');
            $( "#response-message-for-percent" ).removeClass('valid-feedback');
            $('#response-message-for-percent').show()
        } else {
            $('#response-message-for-percent').text(data.message)
            $( "#response-message-for-percent" ).removeClass('invalid-feedback');
            $( "#response-message-for-percent" ).addClass('valid-feedback');
            $('#response-message-for-percent').show()
        }
    })
});

$( ".setScoreInputs" ).keyup(function() {
    let totalScore = 0;
    $('.setScoreInputs').each(function(){
        totalScore = +totalScore + +($(this).val());
        $( "#totalScore" ).text(totalScore);
        if (totalScore !== 100) {
            $( "#totalScore" ).addClass('invalid-feedback');
            $( "#totalScore" ).removeClass('valid-feedback');
            $( "#submit-btn-percent-form" ).attr('disabled', 'disabled');
        } else {
            $( "#totalScore" ).removeClass('invalid-feedback');
            $( "#totalScore" ).addClass('valid-feedback');
            $( "#submit-btn-percent-form" ).attr('disabled', false)
        }
        $( "#totalScore" ).show();
    });
});