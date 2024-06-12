<div data-backdrop="false" class="dest-search-form modal" id="destSearch" tabindex="-1" role="dialog"
     aria-labelledby="destSearch">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body" id="modal-body-search">
                <input type="hidden" class="filters_values">
                <div id="filter_content_0" class="d-none">
	                <?php include (locate_template('template-parts/modal-content/select_several_desires.php')); ?>
                </div>
                <div id="filter_content_1" class="d-none">
	                <?php
	                    $select_title = 'sélectionnez une région';
	                    $is_modal_content = true;
	                    $is_content_clickable = false;
                        include (locate_template('template-parts/modal-content/select_destination.php'));
                    ?>
                </div>
                <div id="filter_content_2" class="d-none">
	                <?php include (locate_template('template-parts/modal-content/select_departure_month.php')); ?>
                </div>
                <div id="filter_content_3" class="d-none">
	                <?php include (locate_template('template-parts/modal-content/select_budget.php')); ?>
                </div>
            </div>
        </div>
    </div>
</div>
