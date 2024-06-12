<?php
function get_block_filters($blocks) {
	ob_start();
	?>
    <div class="d-flex justify-content-center">
        <div class="filters row justify-content-center">
			<?php
			foreach ( $blocks as $key => $block ) {
				$img           = $block['img'] ?? '';
				$first_text    = $block['first_text'] ?? '';
				$second_text   = $block['second_text'] ?? '';
				$style         = 'style="'. $block['style'] . '"';
				$span_style         = isset($block['span_style'])? 'style="'. $block['span_style'] . '"':'' ;
				?>
                <div id="<?php echo $block['id'] .'_'. $key ?>" class="filters-container text-center" <?php echo $style; ?> data-toggle="modal" data-target="#destSearch">
                    <img src="<?php echo $img ?>"/>
                    <div id="<?php echo 'filters-content-' . $key ?>" class="filters-content">
                        <span <?php echo $span_style; ?>><?php _e( $first_text, AFMM_TERMS ) ?></span>
                        <span><?php _e( $second_text, AFMM_TERMS) ?></span>
                    </div>
                </div>
				<?php
			}
			?>
        </div>
    </div>
	<?php
	return ob_get_contents();
}