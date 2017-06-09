/**
 * Created by bor8246 on 17.5.2017.
 *
 * JS file for page
 * admin/create_internal_product.html.twig
 * ajax contains in this file use
 * AppBundle...
 */
var table = null;
$(document).ready(function () {
    //apply
    table = $("#internal_table").DataTable({
           "scrollY":        "200px",
            "scrollCollapse": true,
            "paging":         true
        });

    // create new internal product
    create_internal_product();
});

function create_internal_product(){
    $("#btn_div").on('click', '#btn_create', function () {
        // get data from fields
        // group id
        // group name
        // manufacturer id
        // manufacturer name
        // product name
        // product price
        var groupid = $("#group option:selected").val();
        var groupname = $("#group option:selected").text();
        var manufacturerid = $("#manufacturer option:selected").val();
        var manufacturername = $("#manufacturer option:selected").text();
        var productname = $('#product_name').val();
        var productprice = $('#price').val();

        var type = "create_internal_product";
        var title = "Are you sure you want to create product?";
        var message = "group id:     " + groupid + "<br>"
            + "group name:  " + groupname + "<br>"
            + "manufacturer id:     " + manufacturerid + "<br>"
            + "manufacturer name:     " + manufacturername + "<br>"
            + "product name:     " + productname + "<br>"
            + "product price:     " + productprice + "<br>";
        confirmAction(type, title, message, groupid, groupname, manufacturerid, manufacturername, productname, productprice, $(this));

        // we return false to prevent page reloading
        return false;

    });
}

// Frorm field confirmation
function confirmAction(type, title, message, groupid, groupname, manufacturerid, manufacturername, productname, productprice, selected_button){
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
                case 'create_internal_product':
                    createInternalProduct(result, groupid, groupname, manufacturerid, manufacturername, productname, productprice, selected_button);
                    break;
            }
        }
    });
}

// create internal product AJAX call
function createInternalProduct(result, groupid, groupname, manufacturerid, manufacturername, productname, productprice, selected_button){
    if(result == true){
        if(validate(groupid, groupname, manufacturerid, manufacturername, productname, productprice)){

            // open dialog modal - show information about start of create internal productprice
            // after operation of create internal product is finished it's show message that operation
            // is finished
            open_dialog_info("Please wait until create internal product process is finish.", 'Create internal product process is running...');

            // Ajax call
            $.ajax({
                url: selected_button.data('url'),
                type: 'post',
                dataType: 'json',
                data: { agroupid: groupid, agroupname: groupname, amanufacturerid: manufacturerid, amanufacturername: manufacturername, aproductname: productname, aproductprice: productprice}
            }).done(function (response) {

                if(response.message == 'Internal product already exist in DB! Check in product table!'){
                    // do nothing becouse internal product with that name exists in DB
                }else{
                    // internal product with that name does not exist in DB
                    // add new product like column into table
                    table.row.add([response.id, groupid, groupname, productname, manufacturername, response.date, productprice]).draw();
                }

                // fill dialog info with status information of realized operation
                // prepare dialog info to be able to close
                prepare_to_close_dialog_info('You can close dialog box and continue your work.', response);
            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert('Error : ' + errorThrown);
            });
        }
    }
}

function open_dialog_info(modalLabel, modalStatus){
    //reson for doing that: we don't refresh page after we use modal
    //on second using of modal modal has some values of his last state
    //if we want to have modal value's like we first start modal we must to set that value again
    $("#myModalLabel").text(modalLabel);
    $("#status").text(modalStatus);
    $('.progress-bar').addClass('progress-bar-striped');
    $('.progress-bar').addClass('active');

    //show modal contains information about current process's (import pricelist) progress bar
    $("#progressbar").modal({backdrop: 'static', keyboard: false}, 'show');

    // disable dialog box's close button
    document.getElementById('closebtn').disabled = true;
}

function prepare_to_close_dialog_info(modalStatus, response){
    // enable dialog box's close button
    document.getElementById('closebtn').disabled = false;

    // set modal body with result of import pricelist process (get throw Ajax)
    $("#status").text(response.message + ' ' + modalStatus);
    $(".progress-bar").removeClass('progress-bar-striped');
    $(".progress-bar").removeClass('active');
}

function validate(groupid, groupname, manufacturerid, manufacturername, productname, productprice){
    if(validate_number(groupid, "Group name")){
        if(validate_string(groupname, "Group name")){
            if(validate_number(manufacturerid, "Manufacturer name")){
                if(validate_string(manufacturername, "Manufacturer name")){
                    if(validate_string(productname, "Product name")){
                        if(validate_number(productprice, "Product price")){
                            return true;
                        }else return false;
                    }else return false;
                }else return false;
            }else return false;
        }else return false;
    }else return false;
}

function validate_string(value, name){
    if(value == '' || value == null || value == undefined){
        bootbox.alert(name + " isn't correct.")
        return false;
    }else return true;
}

function validate_number(value, name){
    var rgexp = /^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/;

    if (rgexp.test(value))
        return true;
    else {
        bootbox.alert(name + " isn't correct.")
        return false;
    }
}