function url() {
    return 'https://api.chat-api.com/instance364006/sendMessage?token=h8ewtamgvkq7ijud';
}

function token() {
    return 'h8ewtamgvkq7ijud';
}


function kirimWa(noWa, body
) {

    var data = { //Fetch form data
        'token': token(), //Store name fields value
        'phone': noWa,
        'body': body
    };

    $.ajax({ //Process the form using $.ajax()
        type: 'POST', //Method type
        url: url(), //Your form processing file URL
        data: data, //Forms name
        dataType: 'json',
        success: function (data) {
            if (!data.success) { //If fails
                alert("Notif Berhasil ");
            } else {
                alert("Notif Gagal ");
            }
        }
    });
}