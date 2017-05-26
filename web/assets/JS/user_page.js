/**
 * Created by bor8246 on 24.5.2017.
 */
$(document).ready(function () {
    $('#jstree_div').jstree(
        {
            "core" : {
                "themes" : {
                    "variant" : "small"
                }
            },
            "checkbox" : {
                "keep_selected_style" : false
            },
            "plugins" : [ "wholerow" ]
        }
    );
});