<?php
class Multi_Select_Filter
{
    static function render_filters($list_filters, $select_name, $select_title, $is_clickable = null) {
      $list_filter_index = $select_name;
      ?>
      <select name="<?php echo $select_name ?>" class="multi_select_filter_hidden filter_hidden" select_index="<?php echo $list_filter_index ?>" multiple>
        <?php 
          foreach ($list_filters as $filter) {
            ?>
            <option data-id="<?php echo $filter[3] ?>" value="<?php echo $filter[1] ?>"><?php _e($filter[0], AFMM_TERMS) ?></option>
            <?php
          }
        ?>
      </select>

      <div class="multi_select_filter_title">
        <h2>
            <?php _e($select_title, AFMM_TERMS) ?>
        </h2>
      </div>

      <div class="multi_select_filter filter_<?php echo $list_filter_index ?>">
        <?php
          foreach ($list_filters as $filter) {
            ?>
            <div class="multi_select_filter_item filter_item_<?php echo $list_filter_index. ' ' . $filter[1] ?>" data-id="<?php echo $filter[3] ?>" data-clickable="<?php echo $is_clickable ?>">
                <?php
                    if (!preg_match('/^.*(wp-content).*$/', $filter[2])) {
                        ?>
                        <div class="multi_select_filter_numbers"><?php echo $filter[2]; ?></div>
                        <?php
                    } else { ?>
	                    <div class="multi_select_filter_item_img" style="background-image: url('<?php echo $filter[2]; ?>')"></div>
                    <?php
                    }
                ?>

              <p><?php _e($filter[0], AFMM_TERMS) ?></p>
            </div>
            <?php
          }
          ?>

      </div>
        <?php
    }
}
