<!doctype html>
<html lang="en">

<head>
    <title><?= !empty($title) ? $title . '-' . site_config("site_title", "author") : site_config("site_title", "author") ?></title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <meta name="author" content="<?= site_config('site_description', 'author') ?>">
    <meta name="description" content="<?= site_config('site_description', 'Site desc') ?>">
    <meta name="theme-color" content="#303030">
    <meta name="keywords" content="<?= site_config('site_keywords', "keywords") ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= !empty($title) ? $title . '-' . site_config("site_title", "author") : site_config("site_title", "author") ?>">
    <meta property="og:description" content="<?= site_config('site_description', 'Site desc') ?>">
    <meta property="og:image" content="<?= $og_image ?? get_logo() ?>">
    <meta property="og:url" content="<?= current_url(true) ?>">
    <meta property="og:site_name" content="<?= site_config("site_name", "My Site") ?>">
    <meta property="og:locale" content="en_US">
    <meta name="twitter:card" content="<?= $og_image ?? get_logo() ?>">
    <meta name="twitter:url" content="<?= current_url(true) ?>">
    <meta name="twitter:title" content="<?= !empty($title) ? $title . '-' . site_config("site_title", "author") : site_config("site_title", "author") ?>">
    <meta name="twitter:image" content="<?= $og_image ?? get_logo() ?>">
    <link rel="icon" type="image/png" href="<?= get_logo(true) ?>" />
    <link rel="apple-touch-icon" type="image/png" sizes="76x76" href="<?= get_logo() ?>" />
    <link rel="mask-icon" href="<?= get_logo() ?>" color="#5bbad5" />
    <link rel="canonical" href="<?= base_url() ?>">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&amp;display=swap" rel="stylesheet">
    <!-- FontAwesome JS-->
    <?= script_asset('developers/assets/fontawesome/js/all.min.js') ?>
    <?= link_asset('developers/assets/css/theme.css') ?>


</head>

<body>
    <header class="header fixed-top">
        <div class="branding docs-branding">
            <div class="container-fluid position-relative py-2">
                <div class="docs-logo-wrapper">
                    <button id="docs-sidebar-toggler" class="docs-sidebar-toggler docs-sidebar-visible me-2 d-xl-none" type="button">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <div class="site-logo"><a class="navbar-brand" href="<?= base_url('developers') ?>"><img class="logo-icon me-2" src="<?= get_logo() ?>" alt="logo" style="height: 45px;"></a></div>
                </div><!--//docs-logo-wrapper-->
                <div class="docs-top-utilities d-flex justify-content-end align-items-center">
                    <div class="top-search-box d-none d-lg-flex">
                        <form class="search-form">
                            <input type="text" placeholder="Search the docs..." name="search" class="form-control search-input">
                            <button type="submit" class="btn search-btn" value="Search"><i class="fas fa-search"></i></button>
                        </form>
                    </div>

                    <ul class="social-list list-inline mx-md-3 mx-lg-5 mb-0 d-none d-lg-flex">
                        <li class="list-inline-item"><a href="<?= site_config('social_github_link', 'https://github.com') ?>"><i class="fab fa-github fa-fw"></i></a></li>
                        <li class="list-inline-item"><a href="<?= site_config('social_youtube_link') ?>"><i class="fab fa-youtube fa-fw"></i></a></li>
                        <li class="list-inline-item"><a href="<?= site_config('social_facebook_link') ?>"><i class="fab fa-facebook fa-fw"></i></a></li>
                    </ul><!--//social-list-->
                    <a href="<?= base_url() ?>" class="btn btn-primary d-none d-lg-flex">Get Start</a>
                </div><!--//docs-top-utilities-->
            </div><!--//container-->
        </div><!--//branding-->
    </header><!--//header-->

    <?= view($view) ?>

    <footer class="footer">

        <div class="footer-bottom text-center py-5">

            <ul class="social-list list-unstyled pb-4 mb-0">
                <li class="list-inline-item"><a href="<?= site_config('social_facebook_link') ?>"><i class="fa-brands fa-facebook-f"></i></a></li>
                <li class="list-inline-item"><a href="<?= site_config('social_twitter_link') ?>"><i class="fa-brands fa-twitter"></i></a></li>
                <li class="list-inline-item"><a href="<?= site_config('social_instagram_link') ?>"><i class="fa-brands fa-instagram"></i></a></li>
                <li class="list-inline-item"><a href="<?= site_config('social_pinterest_link') ?>"><i class="fa-brands fa-pinterest"></i></a></li>
            </ul><!--//social-list-->
            <small class="copyright">
                <p class="text-size-16"><?= site_config('copy_right_content') ?></p>
            </small>


        </div>

    </footer>

    <!-- Javascript -->
    <?= script_asset('developers/assets/plugins/popper.min.js') ?>
    <?= script_asset('developers/assets/plugins/bootstrap/js/bootstrap.min.js') ?>
    <?= script_asset('developers/assets/plugins/smoothscroll.min.js') ?>
    <?= script_asset('developers/assets/js/highlight-custom.js') ?>
    <?= script_asset('developers/assets/plugins/simplelightbox/simple-lightbox.min.js') ?>
    <?= script_asset('developers/assets/plugins/gumshoe/gumshoe.polyfills.min.js') ?>
    <?= script_asset('developers/assets/js/docs.js') ?>


</body>

</html>