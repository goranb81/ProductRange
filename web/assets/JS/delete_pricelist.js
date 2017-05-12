/**
 * Created by bor8246 on 25.4.2017.
 *
 *
 */

var table = null;

$(document).ready(function(){
    // submit on delete all button
    // we delete all files related to selected supplier
    delete_all();

    // when we change supplier (select button)
    // table is fill with apropriate supplier's files
    fill_table();

    //apply DataTable jQuery on file table
    table = $("#ftable").DataTable();

    //select more than one row from table
    //and get data from that row
    $('#ftable tbody').on('click', 'tr', function () {
        $(this).toggleClass('selected');
    });

    // delete selected files
    delete_selected();
});

//this function is execute
//after action on submit button
function delete_all(){

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

        confirmAction(type, title, message, null, supplier_name, null, null, null, null, $(this));

        // we return false to prevent page reloading
        return false;
    });

}

// comfirmation dialog box
function confirmAction(type, title, message, data, supplier_name, supplierid, pricelist_filename, nameColumn, priceColumn, selected_button) {
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
                case 'delete_selected':
                    deletePricelistSelected(result, data, selected_button);
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
        $("#myModalLabel").text('Please wait until delete pricelist process is finish.');
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
            //file table is load content
            // when we select supplier
            // when we delete all files
            // we must to clear existing table content
            table.clear().draw();

            // enable dialog box's close button
            document.getElementById('closebtn').disabled = false;

            // set modal body with result of import pricelist process (get throw Ajax)
            $("#status").text('You can close dialog box and continue your work.');
            $(".progress-bar").removeClass('progress-bar-striped');
            $(".progress-bar").removeClass('active');
        }).fail(function (jqXHR, textStatus, errorThrown) {
            alert('Error : ' + errorThrown);
        });
    }

}

function fill_table(){
    $('#supplier').change(function () {
    //reson for doing that: we don't refresh page after we use modal
    //on second using of modal modal has some values of his last state
    //if we want to have modal value's like we first start modal we must to set that value again
    $("#myModalLabel").text('Please wait until file table load is finish.');
    $("#status").text('Load file table elements...');
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
        data: {name: $(this).find(':selected').text()}
    }).done(function (response) {

        add_table_rows_from_JSONdata(response.files);

        // enable dialog box's close button
        document.getElementById('closebtn').disabled = false;

        // set modal body with result of import pricelist process (get throw Ajax)
        $("#status").text('Files table data is load. You can close dialog box and continue your work.');
        $(".progress-bar").removeClass('progress-bar-striped');
        $(".progress-bar").removeClass('active');

    }).fail(function (jqXHR, textStatus, errorThrown) {
        alert('Error : ' + errorThrown);
    });
});
}

// add into table new data from JSON object (response data when we select supplier)
function add_table_rows_from_JSONdata(files){

    // convert JSON response into javascript objects
    var files = jQuery.parseJSON(files);

    // clear existing table content
    table.clear().draw();

    // fill the table with rows
    var t_row = null;
    for (var i=0; i<files.length; i++){
        t_row = create_row(files[i].id, files[i].excelName, files[i].updatedAt.timestamp);
        table.row.add($(t_row)).draw();
    }
}

//return HTML for <tr>
function create_row(id, excelName, updatedAt) {
    var row = '<tr>';
    row += '<td>' + id + '</td>';
    row += '<td>' + excelName + '</td>';
    row += '<td>' + format_date(updatedAt) + '</td>';
    row += '</tr>';
    return row;
}

// this function convert miliseconds to Date
// then convert Date object into string format date
// dd-mm-YY
function format_date(updatedAt){
    var date = new Date(updatedAt* 1000);
    return date.getDate() + '-' + (date.getMonth() + 1) + '-' +  date.getFullYear();
}

function delete_selected(){

    $('#file_div').on("click", "#delete_selected", function(){
        // represent data from selected table' row
        var data = table.rows('.selected').data();

        var type = "delete_selected";
        var title = "Are you want to delete?";
        var message = "Selected pricelist's files.";

        confirmAction(type, title, message, data, null, null, null, null, null, $(this));

        // we return false to prevent page reloading
        return false;
    });
}

function deletePricelistSelected(result, data, selected_button){
    if(result == true){
        // check if at least one row is selected
        if(data[0] === undefined){
            // alert dialog - show message that data is undefined
            // there is no selected rows
            bootbox.alert('There is no selected rows in the table!');
        }else{

            // convert data array into array of pricelistfile's ID's
            var arrayIDs = convertDataToArray(data);
            console.log(arrayIDs);
            //reson for doing that: we don't refresh page after we use modal
            //on second using of modal modal has some values of his last state
            //if we want to have modal value's like we first start modal we must to set that value again
            $("#myModalLabel").text('Please wait until delete pricelist process is finish.');
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
                data: {parrayIDs: arrayIDs}
            }).done(function (response) {
               // delete rows from the table
               // when delete pricelist files from directory
                table.rows( '.selected' )
                    .remove()
                    .draw();

                // enable dialog box's close button
                document.getElementById('closebtn').disabled = false;

                // set modal body with result of import pricelist process (get throw Ajax)
                $("#status").text(response.message + 'You can close dialog box and continue your work.');
                $(".progress-bar").removeClass('progress-bar-striped');
                $(".progress-bar").removeClass('active');
            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert('Error : ' + errorThrown);
            });
        }
    }
}

// convert data array into array of pricelistfile's ID's
function convertDataToArray(data){
    arrayIDs = new Array();
    for(i=0;i<data.length;i++){
        arrayIDs[i] = data[i][0];
    }

    return arrayIDs;
}
