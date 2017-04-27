/**
 * Created by bor8246 on 25.4.2017.
 *
 *
 */

$(document).ready(function(){
    element_on();
});

function element_on(){
    submit_on();
}

//this function is execute
//after action on submit button
function submit_on(){

    $('#file_div').on("click", "#delete", function(){
        // get data from form fields
        var supplier_name = $("#supplier option:selected").text();
        // var supplierid = $("#supplier").val();

        // console.log(supplier_name);
        // console.log(supplierid);
        // console.log(pricelist_filename);
        // importExcelPricelist(true,supplier_name, supplierid, pricelist_filename, $(this));
        var type = "delete";
        var title = "Are you want to delete?";
        var message = supplier_name + "'s pricelist files.";

        confirmAction(type, title, message, supplier_name, null, null, null, null, $(this));

        // we return false to prevent page reloading
        return false;
    });

}

// comfirmation dialog box
function confirmAction(type, title, message, supplier_name, supplierid, pricelist_filename, nameColumn, priceColumn, selected_button) {
    bootbox.confirm({
        title: title,
        message: message,
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            switch (type) {
                case 'delete':
                    deletePricelist(result,supplier_name, supplierid, pricelist_filename, nameColumn, priceColumn, selected_button);
                    break;
            }
        }
    });
}

//send throw AJAX supplier_name
//to /deletepricelist controller action to perform
// delete files action
function deletePricelist(result,supplier_name, supplierid, pricelist_filename, nameColumn, priceColumn, selected_button){
    //if we confirm yes(result = true) call ajax and delete pricelist files
    if (result == true) {

        //reson for doing that: we don't refresh page after we use modal
        //on second using of modal modal has some values of his last state
        //if we want to have modal value's like we first start modal we must to set that value again
        $("#status").text('Delete pricelist process is running...');
        $('.progress-bar').addClass('progress-bar-striped');
        $('.progress-bar').addClass('active');

        //show modal contains information about current process
        $("#progressbar").modal({backdrop: 'static', keyboard: false}, 'show');

        // disable dialog box's close button
        document.getElementById('closebtn').disabled = true;

        $.ajax({
            url: selected_button.data('url'),
            type: 'post',
            dataType: 'json',
            data: {asupplier_name: supplier_name}
        }).done(function (response) {
            // console.log(response.result);

            // enable dialog box's close button
            document.getElementById('closebtn').disabled = false;

            // set modal body with result of import pricelist process (get throw Ajax)
            $("#status").text(' You can close dialog box and continue your work.');
            $(".progress-bar").removeClass('progress-bar-striped');
            $(".progress-bar").removeClass('active');
        }).fail(function (jqXHR, textStatus, errorThrown) {
            alert('Error : ' + errorThrown);
        });
    }

}


