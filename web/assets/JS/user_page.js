/**
 * Created by bor8246 on 24.5.2017.
 */

var table_internal = null;
var table_external = null;
var pad = 5;
$(document).ready(function () {

    // console.dir(window);
    // console.dir(document);
    // console.dir(screen);
    console.log("window_w ");
    console.log($(window).width());
    console.log("window_h");
    console.log($(window).height());
    //
    // alert($(document).width());
    // alert($(document).height());
    //
    // alert($(window.screen).width());
    // alert($(window.screen).height());

    //set web page height and width
    resizeDiv();
    window.onresize = function(event) {
        resizeDiv();
    }

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

function resizeDiv() {
    // first_vpw = $(window).width()/4-2;
    // first_vph = $(window).height()-2;
    // second_vpw = 3*$(window).width()/4-2;
    // second_vph = $(window).height()-2;

    // third_vpw = second_vpw-4;
    // third_vph = 3*second_vph/4-4;
    // fourth_vpw = second_vpw-4;
    // fourth_vph = second_vph/4-4;

    // $("#first").css({'height': first_vph + 'px'});
    // $("#first").css({'width': first_vpw + 'px'});
    // $("#second").css({'height': second_vph + 'px'});
    // $("#second").css({'width': second_vpw + 'px'});

    // $("#third").css({'height': third_vph + 'px'});
    // $("#third").css({'width': third_vpw + 'px'});
    // $("#fourth").css({'height': fourth_vph + 'px'});
    // $("#fourth").css({'width': fourth_vpw + 'px'});

    vpw = $(window).width();
    vph = $(window).height();

    // console.log(vpw);
    // console.log(vph);
    // $("table").css({'height': vph + 'px'});
    // $("table").css({'width': vpw + 'px'});

    first_vpw = $(window).width()/4 - pad;
    first_vph = $(window).height() - pad;
    second_vpw = 3*$(window).width()/4 - pad;
    second_vph = $(window).height() - pad;

    // console.log("first_h" . first_vph);
    // console.log("first_w" . first_vpw);
    // console.log("second_h" . second_vph);
    // console.log("second_w" . second_vpw);

    $("#first").css({'height': first_vph + 'px'});
    $("#first").css({'width': first_vpw + 'px'});
    $("#second").css({'height': second_vph + 'px'});
    $("#second").css({'width': second_vpw + 'px'});


    third_vpw = second_vpw;
    third_vph = 3*second_vph/4;
    fourth_vpw = second_vpw;
    fourth_vph = second_vph/4;

    // console.log("third_h");
    // console.log(third_vph);
    // console.log("third_w");
    // console.log(third_vpw);
    // console.log("fourth_h");
    // console.log(fourth_vph);
    // console.log("fourth_w");
    // console.log(fourth_vpw);

    $("#third").css({'height': third_vph + 'px'});
    $("#third").css({'width': third_vpw + 'px'});
    $("#fourth").css({'height': fourth_vph + 'px'});
    $("#fourth").css({'width': fourth_vpw + 'px'});


}

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
    //Check if internal table is empty
    // then show bootbox alert and dont call Ajax
    if(data == undefined || data == null) bootbox.alert("Internal product table is empty! Please select product group from product tree!");
        else{
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
}