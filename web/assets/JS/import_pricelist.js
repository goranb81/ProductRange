/**
 * Created by bor8246 on 29.3.2017.
 *
 * this js file used by:
 * admin/import_pricelist.html.twig
 * ajax contains in this file use
 * Bee\InputExcelBundle\Controller\ImportExcelController.php -> import_pricelist action
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
        $('#file_div').on("click", "#import", function(){
            // get data from form fields
            var supplier_name = $("#supplier option:selected").text();
            var supplierid = $("#supplier").val();
            var pricelist_filename = $('#excel option:selected').text();

            // console.log(supplier_name);
            // console.log(supplierid);
            // console.log(pricelist_filename);
            importExcelPricelist(true,supplier_name, supplierid, pricelist_filename, $(this));
            // confirmAction("Are you sure to import excel pricelist?", supplier_name, supplierid, pricelist_filename,$(this));
        });

        //pricelist_name has value null. When we select file we changed it's name and
        //pricelist_name than has name of selected file
        // $('#file_div').on("change", "#import", function(e){
        //   pricelist_filename = e.target.files[0].name;
        //   console.log(pricelist_filename);
        // });
    }

    // comfirmation dialog box
    function confirmAction(message, supplier_name, supplierid, pricelist_filename,selected_button) {
        bootbox.confirm({
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
                switch (message) {
                    case "Are you sure to import excel pricelist?":
                        importExcelPricelist(result,supplier_name, supplierid, pricelist_filename, selected_button);
                        break;
                }
            }
        });
    }

    //send throw AJAX supplier_name, supplierid, pricelist_filename
    //to /importpricelist controller action(service bee_input_excel_inputexcel)
    function importExcelPricelist(result,supplier_name, supplierid, pricelist_filename, selected_button){
        //if we confirm yes(result = true) call ajax and import excel pricelist into DB
        if (result == true) {
                $.ajax({
                    url: selected_button.data('url'),
                    type: 'post',
                    dataType: 'json',
                    data: {asupplier_name: supplier_name, asupplierid: supplierid, apricelist_filename: pricelist_filename}
                }).done(function (response) {

                }).fail(function (jqXHR, textStatus, errorThrown) {
                    alert('Error : ' + errorThrown);
                });
        }
    }