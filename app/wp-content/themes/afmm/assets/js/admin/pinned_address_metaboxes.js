jQuery(document).ready(function ($) {
    var meta_boxes = $('#via_fretta_2, #via_fretta_3, #via_fretta_4, #via_fretta_5, #via_fretta_6, #via_fretta_7, #via_fretta_8, #via_fretta_9, #via_fretta_10');
    if (meta_boxes.length) {
        meta_boxes.hide();
    }

    var input_string = '<button type="button" id="btn_plus" class="button button-primary" style="margin-top:10px" >Plus</button><button type="button" id="btn_moins" class="button button-primary" style="display:none;margin-top:10px">Moins</button>'
    $("#via_fretta_1").after(input_string);

    $('#btn_plus').click(function () {
        $("#btn_moins").css("display", "block");
        $("#btn_plus").css("display", "none");
        meta_boxes.show();
    });
    $('#btn_moins').click(function () {
        $("#btn_moins").css("display", "none");
        $("#btn_plus").css("display", "block");
        meta_boxes.hide();
    });

});

