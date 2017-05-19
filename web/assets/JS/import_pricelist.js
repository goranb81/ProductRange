/**
 * Created by bor8246 on 29.3.2017.
 *
 * this js file used by:
 * admin/import_pricelist.html.twig
 * ajax contains in this file use
 * Bee\InputExcelBundle\Controller\ImportExcelController.php -> import_pricelist action
 */

    $(document).ready(function(){
        // this action import excel pricelist file into DB
        import_pricelist_into_DB();

        // fill Excel pricelist files select button
        // with file names after choose supplier
        fill_excel_pricelist_files();

    });

    // this action import excel pricelist file into DB
    function import_pricelist_into_DB(){

        $('#file_div').on("click", "#import", function(){
            // get data from form fields
            var supplier_name = $("#supplier option:selected").text();
            var supplierid = $("#supplier").val();
            var pricelist_filename = $('#excel option:selected').text();
            var nameColumn = $("#name option:selected").text();
            var priceColumn = $("#price option:selected").text();

            // console.log(supplier_name);
            // console.log(supplierid);
            // console.log(pricelist_filename);
            // importExcelPricelist(true,supplier_name, supplierid, pricelist_filename, $(this));
            var type = "import_excel";
            var title = "Are you sure to import excel pricelist?";
            var message = "supplier name:     " + supplier_name + "<br>"
                + "pricelist filename:  " + pricelist_filename + "<br>"
                + "name column:     " + nameColumn + "<br>"
                + "price column:     " + priceColumn + "<br>";
            confirmAction(type, title, message, supplier_name, supplierid, pricelist_filename, nameColumn, priceColumn, $(this));

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
                    case 'import_excel':
                        importExcelPricelist(result,supplier_name, supplierid, pricelist_filename, nameColumn, priceColumn, selected_button);
                        break;
                }
            }
        });
    }

    //send throw AJAX supplier_name, supplierid, pricelist_filename
    //to /importpricelist controller action(service bee_input_excel_inputexcel)
    function importExcelPricelist(result,supplier_name, supplierid, pricelist_filename, nameColumn, priceColumn, selected_button){
        //if we confirm yes(result = true) call ajax and import excel pricelist into DB
        if (result == true) {
            if(validate(supplier_name, supplierid, pricelist_filename, nameColumn, priceColumn)){
                //reson for doing that: we don't refresh page after we use modal
                //on second using of modal modal has some values of his last state
                //if we want to have modal value's like we first start modal we must to set that value again
                $("#myModalLabel").text("Please wait until import pricelist process is finish.");
                $("#status").text('Import pricelist process is running...');
                $('.progress-bar').addClass('progress-bar-striped');
                $('.progress-bar').addClass('active');

                //show modal contains information about current process's (import pricelist) progress bar
                $("#progressbar").modal({backdrop: 'static', keyboard: false}, 'show');

                // disable dialog box's close button
                document.getElementById('closebtn').disabled = true;

                // when start import disable button Start import
                // reason for that is to prevent user to start import before
                // current import is finished
                // document.getElementById("import").disabled = true;

                $.ajax({
                    url: selected_button.data('url'),
                    type: 'post',
                    dataType: 'json',
                    data: {asupplier_name: supplier_name, asupplierid: supplierid, anameColumn: nameColumn, apriceColumn: priceColumn, apricelist_filename: pricelist_filename}
                }).done(function (response) {
                    console.log(response.result);
                    // enable button after proces import pricelist is finished
                    // document.getElementById("import").disabled = false;

                    // hide modal when import pricelist process finished
                    // $("#progressbar").modal('hide');

                    // enable background drop and keyboard esc drop
                    // $("#progressbar").modal({backdrop: true, keyboard: true});

                    // enable dialog box's close button
                    document.getElementById('closebtn').disabled = false;

                    // set modal body with result of import pricelist process (get throw Ajax)
                    $("#status").text(response.result + ' You can close dialog box and continue your work.');
                    $(".progress-bar").removeClass('progress-bar-striped');
                    $(".progress-bar").removeClass('active');
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    alert('Error : ' + errorThrown);
                });
            }

        }

    }

    function validate(supplier_name, supplierid, pricelist_filename, nameColumn, priceColumn){
        if(supplier_name == '' || supplier_name == null || supplier_name == undefined){
            bootbox.alert("Supplier name isn't correct.")
            return false;
        }
        else if(supplierid == '' || supplierid == null || supplierid == undefined){
            bootbox.alert("Supplier id isn't correct.")
            return false;
        }else if(pricelist_filename == '' || pricelist_filename == null || pricelist_filename == undefined){
            bootbox.alert("Pricelist filename isn't correct.")
            return false;
        }else if(nameColumn == '' || nameColumn == null || nameColumn == undefined){
            bootbox.alert("Column name isn't correct.")
            return false;
        }else if(priceColumn == '' || priceColumn == null || priceColumn == undefined){
            bootbox.alert("Column price isn't correct.")
            return false;
        }else return true;
    }

    // fill Excel pricelist files select button
    // with file names after choose supplier
    function fill_excel_pricelist_files(){
        $('#supplier').change(function () {

            //reson for doing that: we don't refresh page after we use modal
            //on second using of modal modal has some values of his last state
            //if we want to have modal value's like we first start modal we must to set that value again
            $("#myModalLabel").text("Please wait until pricelist file's names load is finish.");
            $("#status").text("Load pricelist file's names...");
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

                // clear pricelist filenames
                // clear from select HTML element all option element
                $('#excel').empty();

                // fill select checkbox with filenames for certain supplier
                fill_pricelist_filenames(response.pricelist_filenames);

                // enable dialog box's close button
                document.getElementById('closebtn').disabled = false;

                // set modal body with result of import pricelist process (get throw Ajax)
                $("#status").text('Pricelist filenames was load. You can close dialog box and continue your work.');
                $(".progress-bar").removeClass('progress-bar-striped');
                $(".progress-bar").removeClass('active');

            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert('Error : ' + errorThrown);
            });
        });
    }

    // fill select checkbox with filenames for certain supplier
    function fill_pricelist_filenames(filenames){
        for(i=0;i<filenames.length;i++){
            $('#excel').append($('<option></option>').text(filenames[i]));
        }
    }


