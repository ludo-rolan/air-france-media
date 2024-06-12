jQuery(document).ready(function ($) {
    var meta_boxes = $('#carte_addr_2, #carte_addr_3, #carte_addr_4, #carte_addr_5, #carte_addr_6, #carte_addr_7, #carte_addr_8, #carte_addr_9, #carte_addr_10');
    if (meta_boxes.length) {
        meta_boxes.hide();
    }

    var input_string = '<button type="button" id="btn_plus_carte" class="button button-primary" style="margin-top:10px" >Plus</button><button type="button" id="btn_moins_carte" class="button button-primary" style="display:none;margin-top:10px">Moins</button>'
    $("#carte_addr_1").after(input_string);

    $('#btn_plus_carte').click(function () {
        $("#btn_moins_carte").css("display", "block");
        $("#btn_plus_carte").css("display", "none");
        meta_boxes.show();
    });
    $('#btn_moins_carte').click(function () {
        $("#btn_moins_carte").css("display", "none");
        $("#btn_plus_carte").css("display", "block");
        meta_boxes.hide();
    });

});

