<?php
$infos = get_post_meta($post_id, 'titles', true);
$practical_titles = array(
  "titles_localCalendar",
  "titles_weather",
  "titles_airports",
  "titles_transportation",
  "titles_touristInformation",
  "titles_currency",
  "titles_medical",
  "titles_administrativeProcedures",
  "titles_usefulAddresses",
  "titles_essentialPhrases",
  "titles_goodToKnow"
);
$count = 0;

$ur = $_GET['info_pratiques'];
?>
<div class="bloc" id="infos">
  <h2 class="destination_title"><?php _e('les infos pratiques sur ', AFMM_TERMS);
                                echo $post_title; ?></a></h2>
  <div class="infos">
    <div class="row">
      <div class= <?php echo 'col-md-'.$param; ?>>
        <ul>
          <div class="line"></div>
          <?php foreach ($practical_titles as $key) : $count++; ?>
            <?php
            if ($infos[$key] != "") : ?>
              <?php
              $search = "titles_";
              $id = str_replace($search, '', $key) ;
              $is_Info_page =(isset($_GET['info_pratiques']));
              ?>
              <div class='collapse-container'>
                <li class='collapse-menu'><a href='<?php  echo (!$is_Info_page) ? "?info_pratiques=1#$id":"#$id"?>'> <?php  _e($infos[$key], AFMM_TERMS); ?></a></li>
              </div>
              <?php
              unset($infos[$key]);
              if ($count == 6) break;
              ?>
              <div class="line"></div>
            <?php endif; ?>
          <?php endforeach ?>
          <div class="line bloc-hide"></div>
        </ul>
      </div>
      <div class= <?php echo 'col-md-'.$param; ?>>
        <ul>
          <div class="line <?php echo ($param == 12) ? 'hideElem' : '' ?>"></div>
          <?php foreach ($practical_titles as $key) : ?>
            <?php if ($infos[$key] != "") : ?>
              <?php
              $search = "titles_";
              $id = str_replace($search, '', $key) ;
              ?>
              <div class='collapse-container'>
                <li class='collapse-menu'><a href='<?php  echo (!$is_Info_page) ? "?info_pratiques=1#$id":"#$id"?>'> <?php  _e($infos[$key], AFMM_TERMS); ?></a></li>
              </div>
              <div class="line"></div>
            <?php endif; ?>
          <?php endforeach ?>
        </ul>
      </div>
    </div>
  </div>
</div>