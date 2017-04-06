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

            //show modal contains information about current process's (import pricelist) progress bar
            $("#progressbar").modal({backdrop: 'static', keyboard: false}, 'show');

            // disable dialog box's close button
            document.getElementById('closebtn').disabled = true;

            var status = progressBarAction();
            console.log(status);
            if(status == 'finish'){
                var elem = document.getElementById("prog-bar");
                var width = 0;
                elem.setAttribute("aria-valuenow", width);
                elem.style.width = width + '%';
                console.log(elem.style.width);
                console.log($("#prog-bar").attr("aria-valuenow"));
            }

            // when start import disable button Start import
            // reason for that is to prevent user to start import before
            // current import is finished
            // document.getElementById("import").disabled = true;
            // $.ajax({
            //         url: selected_button.data('url'),
            //         type: 'post',
            //         dataType: 'json',
            //         data: {asupplier_name: supplier_name, asupplierid: supplierid, anameColumn: nameColumn, apriceColumn: priceColumn, apricelist_filename: pricelist_filename}
            //     }).done(function (response) {
            //         console.log(response.result);
            //         // enable button after proces import pricelist is finished
            //         // document.getElementById("import").disabled = false;
            //
            //         // hide modal when import pricelist process finished
            //         // $("#progressbar").modal('hide');
            //
            //         // enable background drop and keyboard esc drop
            //         // $("#progressbar").modal({backdrop: true, keyboard: true});
            //
            //         // enable dialog box's close button
            //         document.getElementById('closebtn').disabled = false;
            //
            //         // set modal body with result of import pricelist process (get throw Ajax)
            //         $("#status").text(response.result + ' You can close dialog box and continue your work.');
            //         // alert(response.result);
            //     }).fail(function (jqXHR, textStatus, errorThrown) {
            //         alert('Error : ' + errorThrown);
            //     });
        }

    }

    // this function change width of progress bar
    // on that way we simulate progress bar action
    function progressBarAction(){
        var elem = document.getElementById("prog-bar");
        var width = 1;
        var id = setInterval(frame, 10);
        function frame() {
            if (width >= 100) {
                clearInterval(id);
                // width = 0;
                // elem.setAttribute("aria-valuenow", width);
                // elem.style.width = width + '%';
            } else {
                width++;
                elem.setAttribute("aria-valuenow", width);
                elem.style.width = width + '%';
            }
        }
    }

