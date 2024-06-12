jQuery(document).ready(function($) {
    $("#lire_suite").on("click", function () {
        $(".intro-content").addClass("intro-content-wf");
        $(".intro-content").removeClass("intro-content");
        $("#lire_suite").css({
            "display":"none",
        });
    });

    // manage maillage using AJAX (only from prod)

    let slug = $("input[name='data-slug']").val();
    let name = $("input[name='data-name']").val();
    let lang = $("input[name='data-lang']").val();
    let host = $("input[name='data-host']").val();

    $.ajax({
        url : host + 'wp-json/afmm/posts/?slug='+slug+'&lang='+lang,
        type: "GET",
        beforeSend: function (xhr) {
            xhr.setRequestHeader ("Authorization", "Basic " + btoa('reworldmedia:reworldmedia'));    
        },
        data: {
            action: "maillage-init",
        },
        success: function (data) {
            parse_html(data['data']);
        }
    });

    function parse_html(data) {
        if (data.length != 0) {
            $('#maillage').append("<h2 class='destination_title'>" + name + "</h2>");
        }
        $('#maillage').append("<div class='row maillage-rw'>");
        if (data.length == 2) {
            $(".maillage-rw").append('<div class="col-md-2"></div>');
        } else if (data.length == 1) {
            $(".maillage-rw").append('<div class="col-md-4"></div>');
        }
        data.forEach(element => {
            let img = element.img
            let link = element.link_post
            let title = element.title
            let cat = element.cat
            let cat_link = element.link_cat

            $('.maillage-rw').append(
                "<div class='col-md-4 post'>" +
                "<a class='post_link' href='" + link + "' target='_blank'>" +
                "<img loading='lazy' alt='" + title + "' class='post_image' src=" + img + " />"
                + "</a>" +
                "<div class='post_info'>" +
                "<h3 class='post_cat'>" +
                "<a href='" + cat_link + "' tatget='_blank'>" + cat + "</a>"
                + "</h3>" +
                "<p class='destination_subtitle'>" +
                "<a href='" + link + "' target='_blank'>" + title + "</a>"
                + "</p>"
                + "</div>"
                + "</div>"
            );
        });
        $('#maillage').append('</div>');
    }

    // Gestion de delay pour les videos de single destination
    
    // 1ere partie pour la video d'introduction : source dailymotion
    // initialisation des variables
    let videoBlocDailymotion = $("#dailymotionVideo");
    let videoIdDailymotion = videoBlocDailymotion.data('videoid');

    // l'ajout de iframe après un delay de 5s
    setTimeout(() => {
        videoBlocDailymotion.append('<iframe loading="lazy" class="dailymotion_video_iframe" src="https://www.dailymotion.com/embed/video/'+videoIdDailymotion+'?disable-queue=true&autoplay=1&mute=true" scrolling="no" allow="autoplay;fullscreen" frameBorder="0"></iframe>');
    }, 2000);
    
})