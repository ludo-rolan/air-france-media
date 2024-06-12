<img src="<?php echo STYLESHEET_DIR_URI . '/assets/img/audio.png' ?>" onclick="document.getElementById('audio_<?php echo $id ?>').play()">
<audio id="audio_<?php echo $id ?>" src="<?php echo wp_get_attachment_url($id); ?>"></audio>
<br>
