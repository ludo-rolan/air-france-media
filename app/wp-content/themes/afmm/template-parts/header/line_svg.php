<?php 
$is_ipad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
if ( (! $site_config['ismobile']  && !$is_ipad) && is_home() ) { 
    $hp_options = get_option("hp_options");
    $habillage_active = $hp_options[apply_filters('get_fields_deps_lang','hp_options_activate_habillage')] ??  '';
    ?>
    <svg version="1.1" id="Calque_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
	    <?php echo $habillage_active == '1' ?  "viewBox='50 0 1000.4 7349.1'" : "viewBox='0 0 1634.4 7349.1'"?>
         style="position:absolute; height: 100%; left: 50%; transform: translateX(-50%); z-index: -1; enable-background:new 0 0 1634.4 7349.1;"
         xml:space="preserve">
    <style type="text/css">
        .st0 {
            display: none;
            transition: stroke-dashoffset 875ms ease-out;
            fill: none;
            stroke: #FF0000;
            stroke-miterlimit: 10;
        }
    </style>
        <path id="svg-path" class="st0" d="M1111.8,1.3c0,0,245.5,140.5,8.9,387.5s-602-57.7-863.8,204.1s-269.2,610.8,122.8,591.6
	c183.5-9,331.1,23.8,421.7,52.3c89.8,28.3,145.2,119.1,127.5,211.6c-12.3,64.8-60,127.4-189.8,147.2
	c-300.2,45.8-394.2,5.2-541.3,152.3S83.9,2175.5,396,2249.4c290.6,68.9,772.1,37,952.5,125.7s341.7,239.6,264.7,409.7
	s121.3,541.3-415.6,604.9s-962.8-1.5-1050.1,189.3s179.9,78.6-35-106.4c-214.9-185-92.5,378.2,153.4,624.1
	s1254.2,306.2,1261.6,662.6c7.4,356.4-1301.5-103.5-1359.2,352s1297.1,497,1313.4,758.7c16.3,261.8-197.3,625.2-509,736.4
	S273,6700.2,249,6953c-16.6,175.5,212,351.4,380.7,394.7"/>
</svg>
<?php } ?>