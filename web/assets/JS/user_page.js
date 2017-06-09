/**
 * Created by bor8246 on 24.5.2017.
 */

var table_internal = null;
var table_external = null;
$(document).ready(function () {
    //apply jQuery datatable
    table_internal = $("#internal_table").DataTable({
        "scrollY":        "600px",
        "scrollCollapse": true,
        "paging":         true
    });
    table_external = $("#external_table").DataTable({
        "scrollY":        "200px",
        "scrollCollapse": true,
        "paging":         true
    });

    /*apply jstree*/
    $('#jstree_div').jstree(
        {
            "core" : {
                "multiple" : false,
                "themes" : {
                    "variant" : "small"
                }
            },
            "checkbox" : {
                "keep_selected_style" : false
            },
            "plugins" : [ "wholerow" ],

        }
    );

    /*clear and fill internal product table
    clear table before filling
    fill table with products belongs to selected node group*/
    clear_and_fill_internal_product_table();

    //select row from internal products table and fill the external product table
    select_row_internal_fill_external();
});

function clear_and_fill_internal_product_table(){
    $('#jstree_div').on('select_node.jstree', function (e, data) {
        // open dialog modal - show information about start of fill internal products
        // after operation of fill products is finished it's show message that operation
        // is finished
        open_dialog_info("Please wait until fill internal products (belong selected nodes) table process is finish.", 'Fill internal products process is running...');

        // AJAX call
        $.ajax({
            url: route,
            type: 'post',
            dataType: 'json',
            data: {nodeid: data.selected}
        }).done(function (response) {
            var products = JSON.parse(response.internalProductsOfSelectedNode);

            /*clear table rows before fill table with new rows
             which represent products belongs to selected node group*/
            table_internal.clear().draw();

            /*clear the external table*/
            table_external.clear().draw();

            /*add all internal products with selected node group*/
            for(i=0;i<products.length;i++){
                // add new product like column into table
                var date = new Date(products[i].inputdate.timestamp*1000).toISOString().replace(/T.*/,'').split('-').reverse().join('-');
                table_internal.row.add([products[i].internalproductid, products[i].groups, products[i].groupname, products[i].productname, products[i].manufacturername, date, products[i].price]).draw();
            }


            // fill dialog info with status information of realized operation
            // prepare dialog info to be able to close
            prepare_to_close_dialog_info('You can close dialog box and continue your work.', response);

        }).fail(function (jqXHR, textStatus, errorThrown) {
            alert('Error : ' + errorThrown);
        });
    });
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
    $("#progressbar").modal('hide');
}

//true - there is selected row in the table
// false - there is no selected row in the table
var is_selected_internal = false;

// selected row
var sel_internal_table_row = null;

//select row from internal products table and fill the external product table
function select_row_internal_fill_external(){
    // select row
    $("#internal_table tbody").on('click', 'tr', function () {
        // if we click on row which is already selected. we desect it
        if($(this).hasClass('selected')){
            //row is deselected. that means there is no selected row in the table
            is_selected_internal = false;

            // remove supplier external product table content
            table_external.clear().draw();

            // deselect row
            $(this).removeClass('selected');

            sel_internal_table_row = null;
            // row is not selected. deselect selected row and select current
        }else{
            //row is selected
            is_selected_internal = true;

            table_internal.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');

            var data = table_internal.row(this).data();
            // fill supplier external product table content
            fill_supplier_external_product_table(data);

            sel_internal_table_row = this;
        }
    });
}

function fill_supplier_external_product_table(data){
    console.log(data);
    // open dialog modal - show information about start of fill supplier products
    // after operation of fill products is finished it's show message that operation
    // is finished
    open_dialog_info("Please wait until fill supplier products table process is finish.", 'Fill supplier products process is running...');

    // AJAX call
    $.ajax({
        url: route1,
        type: 'post',
        dataType: 'json',
        data: {productid: data[0]}
    }).done(function (response) {
        var products = JSON.parse(response.externalProductsOfSelectedInternalProduct);

        /*clear table rows before fill table with new rows
         which represent supplier external products belongs to selected internal product*/
        table_external.clear().draw();

        /*add all supplier external products belong selected internal product*/
        for(i=0;i<products.length;i++){
            // add new product like column into table
            var date = new Date(products[i].inputdate.timestamp*1000).toISOString().replace(/T.*/,'').split('-').reverse().join('-');
            table_external.row.add([products[i].externalproductid, products[i].productname, products[i].price, products[i].supplierid, products[i].suppliername, date, products[i].status]).draw();
        }

        // fill dialog info with status information of realized operation
        // prepare dialog info to be able to close
        prepare_to_close_dialog_info('You can close dialog box and continue your work.', response);

    }).fail(function (jqXHR, textStatus, errorThrown) {
        alert('Error : ' + errorThrown);
    });
}