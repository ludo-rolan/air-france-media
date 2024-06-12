<?php
/**
 * Template Name: Credits photo
 */
get_header();

$directory= ABSPATH.'import_data/image_credits/credits_by_alphabet/';
$index_choices =array_merge(['index','#'] ,range('A', 'Z'));
$credits = [];



foreach($index_choices as $key=>$choice){
    if($key=='index'){
        continue;
    }
    $credits[$choice][]='Crédits';
    if (($open = fopen($directory.$choice.'.csv', "r")) !== FALSE) {
        $count = 0;
        while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
            if ($count != 0) {
                $credits[$choice][] =  $data['0'];
            }
            $count++;
        }
        fclose($open);
    }
}
//var_dump($credits);
$count = 0;
?>
    <div class="container">
        <section class="infosPratiques_breadcrumb">
            <div class="container">
			    <?php get_breadcrumb(); ?>
                <span class="credits-photo__title"><?php _e('remerciements', AFMM_TERMS); ?></span>
            </div>
        </section>
        <div class="row credits-photo-body">
            <div class="col-md-12 mb-3">
                <span class="credits-photo-body__title"><?php _e('Nous tenons à remercier les photographes et vidéastes pour leur contribution au guide de voyage ENVOLS par Air France.', AFMM_TERMS); ?></span>
            </div>
            <div class="col-md-4">
                <div class="credits-photo__alphabet" >
                    <div class="infos">
                        <div class="row">
                            <div class="col-md-12">
                                <ul>
						            <?php foreach ($index_choices as $choice) : ?>
                                            <div class='collapse-container alphabet' data-alphabet="<?php echo $choice?>" >
                                                <?php if ($choice === 'index') : ?>
                                                    <span id="index" data-alphabet="<?php echo $choice?>" class='credits-photo-body__index'><?php _e($choice, AFMM_TERMS); ?></span>
                                                <?php else : ?>
                                                    <li data-alphabet="<?php echo $choice?>"  class='collapse-menu'><a  href='#<?php echo $choice?>'> <?php  _e($choice, AFMM_TERMS); ?></a></li>
                                                <?php endif; ?>
                                            </div>
                                            <div class="line"></div>
						            <?php endforeach ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8" id="infos">
                    <div class="infos" >
                        <div class="row">
                            <div class="col-md-12">
                                <ul>
						            <?php foreach ($index_choices as $key) : 
                                     if($key=='index'){
                                        continue;
                                     }?>
                                      <div class='collapse-container credits' id="<?php echo $key?>" data-alphabet="<?php echo $key?>" >
                                            
                                                <?php 
                                                foreach ($credits[$key] as $content) :
                                                ?>
                                                <li class='collapse-menu'><a > <?php _e($content, AFMM_TERMS); ?></a></li>
                                                <div class="line"></div>
								            <?php endforeach;
								                ?>
                                        </div>
						            <?php endforeach ?>
                                </ul>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
<?php
get_footer();