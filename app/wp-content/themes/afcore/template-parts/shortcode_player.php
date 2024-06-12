<?php
$vimeo_id = $attrs['vimeo_id'];
$dailymotion_id = $attrs['dailymotion_id'];
$youtube_id = $attrs['youtube_id'];
$poster = $attrs['poster'];
$src = $attrs['src'];
$autoplay = $attrs['autoplay']=="true";

$video_provider = "";
$video_id = "";
if(!empty($src)){
	$video_provider="local";
	$video_id = $src;
}elseif (!empty($vimeo_id)){
	$video_provider = "vimeo";
	$video_id = $vimeo_id;
}
elseif (!empty($dailymotion_id)){
	$video_id = $dailymotion_id;
}
elseif (!empty($youtube_id)){
	$video_provider = "youtube";
	$video_id = $youtube_id;
}else{
	return;
}

$config = array(
	"iconUrl" => '/wp-content/themes/afcore/assets/img/plyr.svg',
	"autoplay" => $autoplay,
);
//get unique uuid for new player
$uuid = uniqid();
?>
<div class="plyr-wrapper">
    <?php if($video_provider=="local"){ ?>
        <video id="plyr-<?php echo $uuid; ?>" class="plyr" poster="<?php echo $poster; ?>" controls>
            <source src="<?php echo $src; ?>" type="video/mp4">
        </video>
    <?php }
    elseif(!empty($dailymotion_id)) {
        wp_enqueue_script('video-dailymotion', AF_THEME_DIR_URI . '/assets/js/front/video_dailymotion.js', array( 'jquery' ),CACHE_VERSION_CDN );
        ?>
        <div class="plyr plyr-dailymotion">
            <iframe  
                loading = "lazy"
                class="plyr-dailymotion-iframe"
                src="https://www.dailymotion.com/embed/video/<?php echo $dailymotion_id; ?>?mute=true" 
                scrolling="no" 
                allow="fullscreen" 
                frameBorder="0"
                style="width: auto !important;"
            >
            </iframe>
        </div>
        <?php 
    }
    else{ ?>
        <div
                id="plyr-<?php echo $uuid; ?>"
                data-plyr-provider="<?php echo $video_provider; ?>"
                data-plyr-embed-id="<?php echo $video_id; ?>"
        >
        </div>
    <?php } ?>
</div>
<script type="text/javascript">
    const player = new Plyr('#plyr-<?php echo $uuid?>', {
        iconUrl: '<?php echo $config['iconUrl']; ?>',
        autoplay: <?php echo $config['autoplay'] ? 'true' : 'false'; ?>,
    });
</script>
