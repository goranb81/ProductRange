/**
 * Created by bor8246 on 8.5.2017.
 * this file used by:
 * admin/upload_pricelist.html.twig
 */

$(document).ready(function () {
    //remove label to customize upload
    $("label[for='form_excelFile_file']").remove();

    // add classes to customize input file
    $("#form_excelFile_file").addClass('btn');
    $("#form_excelFile_file").addClass('btn-primary');

    // add classes to customize Upload pricelist button
    $("#form_upload").addClass('btn');
    $("#form_upload").addClass('btn-primary');
});