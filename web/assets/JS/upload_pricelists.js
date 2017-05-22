/**
 * Created by bor8246 on 8.5.2017.
 * this file used by:
 * admin/upload_pricelist.html.twig
 */

// FROM PREVIOUS VERSION WITH SYMFONY FORM!!!!
// $(document).ready(function () {
//     // uplosd pricelist excel file
//     upload_pricelist_excel_file();
// });
//
// function upload_pricelist_excel_file(){
//     $('#form').on('click', '#upload', function () {
//         var file = $('#file_id').val();
//         var supplierid = $('#supplier option:selected').val();
//         var suppliername = $('#supplier option:selected').text();
//
//         console.log(file);
//         console.log(supplierid);
//         console.log(suppliername);
//         // we return false to prevent page reloading
//         return false;
//     });
// }

$(document).ready(function () {
    //upload file
    upload_file();
});

function upload_file(){
    $('form').submit(function (e) {
        var currentForm = this;
        //disable submit
        e.preventDefault();

        var file = $('#file_id').val();
        // var supplierid = $('#supplier option:selected').val();
        var suppliername = $('#supplier option:selected').text();

        var title = 'Are you want to upload?';
        var message = 'Info' + '<br>'
            + 'file: ' + file + '<br>'
            + 'supplier: ' + suppliername + '<br>';

        //when return true submit action is complete
        // when we return false submit action is stay disabled
        return  bootbox.confirm({
            title: 'Would you like to upload file?',
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
                if(result){
                    if(validate(file, suppliername)){
                        currentForm.submit();
                    }
                }
            }
        });

    });
}

function validate(file, suppliername){
    if(validate_string(file, 'File name')){
        if(validate_string(suppliername, 'Supplier name')){
            return true;
        }else return false;
    }else return false;
}

function validate_string(value, name){
    if(value == '' || value == null || value == undefined){
        bootbox.alert(name + " isn't correct. Check form input.")
        return false;
    }else return true;
}
