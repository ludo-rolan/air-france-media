jQuery(document).ready(function($) {
    $("#resize_thumb .inside").append(`
    <div class="resize_thumb_preview">
        <div class="resize_thumb_preview_img"
            style="background-image: url('` + thumb_preview.image + `');">
        </div>
        <div class="resize_thumb_preview_after">
            <button type="button" class="resize_thumb_refresh"><img src="`+ thumb_preview.refresh +`" /></button>
        </div>
    </div>
    <style>
      .resize_thumb_preview {
        width: 300px;
        margin-left: -25px
      }
      .resize_thumb_preview_img {
        position: relative;
        width: 320px;
        background-size: cover;
        height: 470px;
        background-position: top !important;
        transform: scale(0.8);
      }
      .resize_thumb_preview_after {
          width: 300px;
          height: 120px;
          margin-top: -120px;
          background-color: #ffffff;
          position: absolute;
          display: flex;
          justify-content: center;
          align-items: center;
      }
    </style>
    `);

    $('.resize_thumb_refresh').click(function() {
        let new_thumb = $('.editor-post-featured-image img').attr('src');
        $('.resize_thumb_preview_img').css('background-image', 'url("'+ new_thumb +'")');
    });
});