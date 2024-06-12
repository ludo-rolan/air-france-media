
<div class="nav-mobile-icon-container">
    <button class="navbar-toggler nav-mobile-burger-icon nav-mobile-button" type="button" data-toggle="collapse" data-target="#navbarMobileContent" aria-controls="navbarMobileContent" aria-expanded="false" aria-label="Toggle navigation" id='navbarMobileContentBtn'>
        <div class="nav-mobile-burger-out nav-mobile-burger-out-top"></div>
        <div class="nav-mobile-burger-in nav-mobile-burger-in-top"></div>
        <div class="nav-mobile-burger-in nav-mobile-burger-in-bottom"></div>
        <div class="nav-mobile-burger-out nav-mobile-burger-out-bottom"></div>
    </button>
</div>
<div>
    <a href="<?php echo IS_PREPROD ? $site_config['afmm']['preprod'] : $site_config['afmm']['prod']; ?>">
        <img class="nav-mobile-logo" src="<?php echo $site_config['logo_header'] ?>" alt="">
    </a>
</div>

<div class="nav-mobile-icon-container">
    <button data-toggle="modal" data-target="#MainSearch" class="navbar-toggler nav-mobile-search-button nav-mobile-button" type="button" 
    aria-controls="MainSearch" aria-expanded="false" aria-label="Toggle navigation" id="navbarMobileSearchBtn"
    <?php echo (PROJECT_NAME == 'aftg') ? "style= display:none;":"" ?>
    >
        <div class="nav-mobile-search-icon">
            <span class="nav-mobile-search-line"></span>
            <span class="nav-mobile-search-circle"></span>
            <span class="nav-mobile-search-line second-line"></span>
        </div>
    </button>
</div>
