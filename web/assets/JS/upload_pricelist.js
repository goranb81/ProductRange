/**
 * Created by bor8246 on 29.3.2017.
 *
 * this js file used by:
 * admin/upload_pricelist1.html.twig
 * ajax contains in this file use
 * AppBundle\Controller\AdminController.php -> uploadpricelist1Action
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

        $('#file_div').on("click", "#upload", function(){
            // get data from form fields
            var supplier_name = $("#supplier option:selected").text();
            var pricelist_filename = $('#pricelist_filename').val();


            var type = "upload_excel";
            var title = "Are you sure to upload excel pricelist?";
            var message = "supplier name:     " + supplier_name + "<br>"
                + "pricelist filename:  " + pricelist_filename + "<br>";
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
                    case 'upload_excel':
                        uploadExcelPricelist(result,supplier_name, supplierid, pricelist_filename, nameColumn, priceColumn, selected_button);
                        break;
                }
            }
        });
    }

    //send throw AJAX supplier_name
    //to /upload_pricelist_page1 controller action
    function uploadExcelPricelist(result,supplier_name, supplierid, pricelist_filename, nameColumn, priceColumn, selected_button){
        //if we confirm yes(result = true) call ajax
        if (result == true) {

            $.ajax({
                    url: selected_button.data('url'),
                    type: 'post',
                    dataType: 'json',
                    data: {asupplier_name: supplier_name}
                }).done(function (response) {
                    // console.log(response.result);
                    // // enable button after proces import pricelist is finished
                    // // document.getElementById("import").disabled = false;
                    //
                    // // hide modal when import pricelist process finished
                    // // $("#progressbar").modal('hide');
                    //
                    // // enable background drop and keyboard esc drop
                    // // $("#progressbar").modal({backdrop: true, keyboard: true});
                    //
                    // // enable dialog box's close button
                    // document.getElementById('closebtn').disabled = false;
                    //
                    // // set modal body with result of import pricelist process (get throw Ajax)
                    // $("#status").text(response.result + ' You can close dialog box and continue your work.');
                    // $(".progress-bar").removeClass('progress-bar-striped');
                    // $(".progress-bar").removeClass('active');
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    alert('Error : ' + errorThrown);
                });
        }

    }

