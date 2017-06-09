/**
 * Created by bor8246 on 28.4.2017.
 */

var external_table = null;
var internal_table = null;
$(document).ready(function (){
    // table that represent pricelist table in DB (pricelists products)
  external_table = $("#external_table").DataTable({
      "scrollY":        "200px",
      "scrollCollapse": true,
      "paging":         true
  });
    // table that represent products table in DB. products which we record like our internal products
  internal_table = $("#internal_table").DataTable({
      "scrollY":        "200px",
      "scrollCollapse": true,
      "paging":         true
  });

    // this function apply: when we change supplier in suuplier's select element
    // external products table change it's content with appropriate supplirs content
    list_products_pricelits();

    // select row in external products table fill external products info in Linking part
    select_row_fill_info_external();

    // select row in internal products table fill internal products info in Linking part
    select_row_fill_info_internal();

    // linked external product from pricelist to internal product
    link_products();

    // send external(priselist) product into trash
    sent_to_trash();

    //provide double size of search filter - internal table
    $('#internal_table_filter').children().children().width(500);

    /*provide double size of search filter - external table*/
    $('#external_table_filter').children().children().width(500);

});

// get all products from DB pricelits table
// condition is supplier ID
// fill the external products table
function list_products_pricelits(){
  $('#supplier_choose').change(function () {
      //remove from Linking part info about external product
      //sometimes that part is fill
      remove_info();

      // when we change supplier we load new products
      // there is no row which is selected
      sel_external_table_row = null;

      //reson for doing that: we don't refresh page after we use modal
      //on second using of modal modal has some values of his last state
      //if we want to have modal value's like we first start modal we must to set that value again
      $("#status").text('Load pricelist table element...');
      $('.progress-bar').addClass('progress-bar-striped');
      $('.progress-bar').addClass('active');

      //show modal contains information about current process
      $("#progressbar").modal({backdrop: 'static', keyboard: false}, 'show');

      // disable dialog box's close button
      document.getElementById('closebtn').disabled = true;

      // AJAX call
      $.ajax({
          url: $(this).find(':selected').data('url'),
          type: 'post',
          dataType: 'json',
          data: {id: this.value}
      }).done(function (response) {

          add_table_rows_from_JSONdata(response.products);

          // enable dialog box's close button
          document.getElementById('closebtn').disabled = false;

          // set modal body with result of import pricelist process (get throw Ajax)
          $("#status").text('Pricelist table data is load. You can close dialog box and continue your work.');
          $(".progress-bar").removeClass('progress-bar-striped');
          $(".progress-bar").removeClass('active');

      }).fail(function (jqXHR, textStatus, errorThrown) {
          alert('Error : ' + errorThrown);
      });
  });
}

// add into table new data from JSON object (response data when we select supplier)
function add_table_rows_from_JSONdata(products){

    // convert JSON response into javascript objects
    var products = jQuery.parseJSON(products);

    // clear existing table content
    external_table.clear().draw();

    // fill the table with rows
    var t_row = null;
    for (var i=0; i<products.length; i++){
        t_row = create_row(products[i].externalproductid, products[i].productname, products[i].price);
        external_table.row.add($(t_row)).draw();
    }

}


//return HTML for <tr>
function create_row(id, productname, price) {
    var row = '<tr>';
    row += '<td>' + id + '</td>';
    row += '<td>' + productname + '</td>';
    row += '<td>' + price + '</td>';
    row += '</tr>';
    return row;
}

//true - there is selected row in the table
// false - there is no selected row in the table
var is_selected_external = false;

// selected row
var sel_external_table_row = null;

//select row from internal products table and fill the internal product info
function select_row_fill_info_external(){
    // select row
    $("#external_table tbody").on('click', 'tr', function () {
        // if we click on row which is already selected. we desect it
        if($(this).hasClass('selected')){
            //row is deselected. that means there is no selected row in the table
            is_selected_external = false;

            // remove info in Linking part
            remove_info();
            // deselect row
            $(this).removeClass('selected');

            sel_external_table_row = null;
        // row is not selected. deselect selected row and select current
        }else{
               //row is selected
                is_selected_external = true;

                external_table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');

                var data = external_table.row(this).data();
                // fill external info
                fill_info(data);

                // selected row
                // use this var in following situation:
                // when we linked external product with internal
                // we delete external product row from table
                sel_external_table_row = this;
        }

    });
}

// remove info about external (pricelist) products
function remove_info(){
    $("#le_id").text('');
    $("#lsupplier_name").text('');
    $("#lproduct_name").text('');
    $("#lprice").text('');
}

// fill info about external (pricelist) products
function fill_info(data){
    $("#le_id").text(data[0]);
    $("#lsupplier_name").text($('#supplier_choose option:selected').text());
    $("#lproduct_name").text(data[1]);
    $("#lprice").text(data[2]);
}

//true - there is selected row in the table
// false - there is no selected row in the table
var is_selected_internal = false;

// selected row
var sel_internal_table_row = null;

//select row from external products table and fill the external product info
function select_row_fill_info_internal(){
    // select row
    $("#internal_table tbody").on('click', 'tr', function () {
        // if we click on row which is already selected. we desect it
        if($(this).hasClass('selected')){
            //row is deselected. that means there is no selected row in the table
            is_selected_internal = false;

            // remove info in Linking part
            remove_info_internal();
            // deselect row
            $(this).removeClass('selected');

            sel_internal_table_row = null;
            // row is not selected. deselect selected row and select current
        }else{
            //row is selected
            is_selected_internal = true;

            internal_table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');

            var data = internal_table.row(this).data();
            // fill external info
            fill_info_internal(data);

            sel_internal_table_row = this;
        }
    });
}

// remove info about internal products
function remove_info_internal(){
    $('#lid').text('');
    $('#lgroup').text('');
    $('#liproduct_name').text('');
    $('#lmanufacturer').text('');
    $('#linput_date').text('');
    $('#liprice').text('');
}

// fill info about internal products
function fill_info_internal(data){
    $('#lid').text(data[0]);
    $('#lgroup').text(data[1]);
    $('#liproduct_name').text(data[2]);
    $('#lmanufacturer').text(data[3]);
    $('#linput_date').text(data[4]);
    $('#liprice').text(data[5]);
}

// link external with internal product
function link_products(){
    $('#btn_link').click(function () {
        //variable for confirmation

        var internal_product = {
            id: $('#lid').text(),
            group: $('#lgroup').text(),
            name: $('#liproduct_name').text(),
            manufacturer: $('#lmanufacturer').text(),
            input_date: $('#linput_date').text(),
            price: $('#liprice').text()
        };
        var external_product = {
            id: $("#le_id").text(),
            supplier_name: $("#lsupplier_name").text(),
            name: $("#lproduct_name").text(),
            price: $("#lprice").text()
        };

        var type = 'linking';
        var title = 'Are you want to linking products?';
        var message = 'Internal product' + '<br>'
            + 'id: ' + internal_product.id + '<br>'
            + 'group: ' + internal_product.group + '<br>'
            + 'name: ' + internal_product.name + '<br>'
            + 'manufacturer: ' + internal_product.manufacturer + '<br>'
            + 'price: ' + internal_product.price + '<br><br>';

        message += 'External product: '
            + 'id: ' + external_product.id + '<br>'
            + 'supplier name: ' + external_product.supplier_name + '<br>'
            + 'name: ' + external_product.name + '<br>'
            + 'price: ' + external_product.price;

        // confirmation dialog box
        confirmAction(type, title, message, external_product, internal_product);
    });
}

// comfirmation dialog box
function confirmAction(type, title, message, external_product, internal_product) {
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
                case 'linking':
                    link(result, external_product, internal_product);
                    break;
                case 'trash':
                    trash(result, external_product);
                    break;
            }
        }
    });
}

//Ajax call link_products - AppBundle/AdminController.php action
function link(result, external_product, internal_product){
    // YES option on confirmation box
    if(result == true){
        // to call Ajax we can fulfill following condition:
        // row in external table must be selected
        // row in internal table must be selected


            if(validate(external_product, internal_product, 'linking')){
                // disable link btn until linked process finish
                $('#btn_link').attr("disabled", true);

                // AJAX call
                $.ajax({
                    url: $('#btn_link').data('url'),
                    type: 'post',
                    dataType: 'json',
                    data: {aexternal_product: external_product, ainternal_product: internal_product}
                }).done(function (response) {
                    // deselect internal table row
                    // deselect external table row
                    // reson: after linked products, products info still has values
                    // when we deselect rows we suspend possibility to try link already linked products
                    $(sel_internal_table_row).removeClass('selected');
                    $(sel_external_table_row).removeClass('selected');

                    // remove info in Linking part
                    remove_info_internal();
                    // remove info in Linking part
                    remove_info();

                    // delete row(linked external product) from table
                    external_table.row(sel_external_table_row).remove().draw();

                    // enable link btn after linked process is finished
                    $('#btn_link').attr("disabled", false);

                    bootbox.alert(response.message);

                }).fail(function (jqXHR, textStatus, errorThrown) {
                    alert('Error : ' + errorThrown);
                });
            }

    }

}

function validate(external_product, internal_product, type){

    if (type === 'linking') {
        return validate_linking(external_product, internal_product);
    }
    else if (type === 'trash') {
         return validate_trash(external_product);
    }

}

function validate_linking(external_product, internal_product){
    //     // to call Ajax we can fulfill following condition:
    //     // row in external table must be is_selected_external
    //     // row in internal table must be selected
    if(is_selected_internal == false && is_selected_external == false){
        bootbox.alert("Both external and internal table's row is not selected. Both external and internal product's info is incorrect!" );
        return false;
    }else if(is_selected_external == false){
        bootbox.alert("External table's row is not selected. External product's info is incorrect!" );
        return false;
    }else if(is_selected_internal == false){
        bootbox.alert("Internal table's row is not selected. Internal product's info is incorrect!" );
        return false;
    }else
    // external product's id must be defined
    if(external_product.id == '' || external_product.id == null || external_product.id == undefined){
        bootbox.alert("External product's info is incorrect!" );
        return false;
    } else
    // internal product' id must be defined'
    if(internal_product.id == '' || internal_product.id == null || internal_product.id == undefined) {
        bootbox.alert("Internal product's info is incorrect!");
        return false;
    }else return true;
}

function validate_trash(external_product){
        // to call Ajax we can fulfill following condition:
        // row in external table must be is_selected_external
    if(is_selected_external == false) {
        bootbox.alert("External table's row is not selected. External product's info is incorrect!");
        return false;
    }else // external product's id must be defined
        if(external_product.id == '' || external_product.id == null || external_product.id == undefined){
            bootbox.alert("External product's info is incorrect!" );
            return false;
        } else return true;
}


function sent_to_trash(){
    $('#send_trash').click(function () {
        //variable for confirmation

        var external_product = {
            id: $("#le_id").text(),
            supplier_name: $("#lsupplier_name").text(),
            name: $("#lproduct_name").text(),
            price: $("#lprice").text()
        };

        var type = 'trash';
        var title = 'Are you want to trash product?';
        message = 'External product: '
            + 'id: ' + external_product.id + '<br>'
            + 'supplier name: ' + external_product.supplier_name + '<br>'
            + 'name: ' + external_product.name + '<br>'
            + 'price: ' + external_product.price;

        // confirmation dialog box
        confirmAction(type, title, message, external_product, null);
    });
}

function trash(result, external_product){
    // YES option on confirmation box
    if(result == true) {
        // to call Ajax we can fulfill following condition:
        // row in external table must be is_selected_external

        if (validate(external_product, null, 'trash')) {
            // disable send trash btn until trash process finish
            $('#send_trash').attr("disabled", true);

            // AJAX call
            $.ajax({
                url: $('#send_trash').data('url'),
                type: 'post',
                dataType: 'json',
                data: {aexternal_product: external_product}
            }).done(function (response) {
                // deselect internal table row
                // deselect external table row
                // reson: after trashed products, products info still has values
                // when we deselect rows we suspend possibility to try link or trash
                // already trashed product
                $(sel_internal_table_row).removeClass('selected');
                $(sel_external_table_row).removeClass('selected');

                // remove info in Linking part
                remove_info_internal();
                // remove info in Linking part
                remove_info();

                // delete row(linked external product) from table
                external_table.row(sel_external_table_row).remove().draw();

                // enable link btn after linked process is finished
                $('#send_trash').attr("disabled", false);

                bootbox.alert(response.message);

            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert('Error : ' + errorThrown);
            });
        }
    }
}



