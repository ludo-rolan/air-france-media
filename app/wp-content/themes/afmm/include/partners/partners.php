<?php
//TODO: Create a Partner like System
add_shortcode("edisound", "implementation_edisound");

function implementation_edisound($attrs)
{
	if (!empty($attrs['data_pid'])) {
		$data_pid = $attrs['data_pid'];
		return <<<EDISOUND
				<div class="rwm-podcast-player" data-pid="$data_pid"></div>
				<script type="text/javascript" src="https://publishers.edisound.com/player/javascript/init.js" async></script>
			EDISOUND;
	}
}