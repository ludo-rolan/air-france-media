jQuery(document).ready(function($) {
    $("#envie").on("mouseover", function () {
        $(".envies").css({"display":"block"});
    });

    $("#select_desire_btn_3").click(function(){
        $('#destSearch').modal('hide');
    });

    $(".increment_num").click(function(){
        let passenger_num = $("#passenger_num");
        let passenger_count = parseInt(passenger_num.val());
        if (passenger_count < 9) {
            passenger_count++;
            passenger_num.val(passenger_count);
            $(".passenger_count").text(passenger_count);
        }

        if (passenger_count > 1) {
            $('.passenger_content span:last-child').html('voyageurs');
        }
    });

    $(".decrement_num").click(function(){
        let passenger_num = $("#passenger_num");
        let counter = parseInt(passenger_num.val());
        if (counter < 3) {
            $('.passenger_content span:last-child').html('voyageur');
        }
        if (counter !== 1) {
            counter--;
            passenger_num.val(counter);
            $(".passenger_count").text(counter);
        }
    });

    function getSlidersVals(){
        let slide1 = parseInt( $('.select_budget_slide_1').val() );
        let slide2 = parseInt( $('.select_budget_slide_2').val() );
        // Neither slider will clip the other, so make sure we determine which is larger
        if( slide1 > slide2 ){
            let tmp = slide2;
            slide2 = slide1;
            slide1 = tmp;
        }
        $('.select_budget_range_value_1').html(slide1 + "€");
        $('.select_budget_range_value_2').html(slide2 + "€");
        $('#price_num').val(slide1+","+ slide2 );
    }

    $(window).on('load', function () {
        // Initialize Sliders
        let sliders = $(".select_budget_range-slider input");
        sliders.each(function (index) {
            sliders[index].oninput = getSlidersVals;
            sliders[index].oninput();
        });
    })

    $(document).mouseup(function(e) {
        let container = $("#search_input");
        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            $('.suggestions-opening').css('display', 'none')
            $('.has-suggestions').css('display', 'none')
        } else {
            $('.suggestions-opening').css('display', 'block')
            $('.has-suggestions').css('display', 'block')
        }
    });
});