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
    <meta property="og:image" content="<?= $og_image ?? get_logo() ?>" style="max-width: 100%; height: auto;">
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <?= link_asset('frontend/vendor/aos/aos.css') ?>
    <?= link_asset('frontend/vendor/bootstrap/css/bootstrap.min.css') ?>
    <?= link_asset('frontend/vendor/bootstrap-icons/bootstrap-icons.css') ?>
    <?= link_asset('frontend/vendor/glightbox/css/glightbox.min.css') ?>
    <?= link_asset('frontend/vendor/remixicon/remixicon.css') ?>
    <?= link_asset('frontend/vendor/swiper/swiper-bundle.min.css') ?>
    <?= link_asset('frontend/css/style.css') ?>

    

</head>

<style>
.integration-fixed {
    position: fixed;
    z-index: 10000000;
}
.integration-fixed__bottom-right {
    bottom: 0;
    right: 0;
}
.whatsapp-container {
    padding: 24px;
}
.whatsapp-button {
    width: 60px;
    height: 60px;
    background-color: #25d366;
    color: #FFF !important;
    border-radius: 50px;
    text-align: center;
    font-size: 30px;
    box-shadow: 2px 2px 3px #999;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none !important;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    transition: all 0.3s ease;
    transform: scale(0.9);
    position: fixed;
    bottom: 26px;
    right: 15px;
}
.whatsapp-button:hover {
    transform: scale(1);
    background-color: #108f29;
}



</style>
<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top">
        <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

            <a href="<?= base_url(); ?>" class="logo d-flex align-items-center">
                <img src="<?= get_logo() ?>" alt="">
            </a>

            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
                    <li><a class="nav-link scrollto" href="#about">About</a></li>
                    <li><a class="nav-link scrollto" href="#services">Services</a></li>
                    <li><a class="nav-link scrollto" href="#pricing">Pricing</a></li>
                    <li><a href="<?= base_url('blogs') ?>">Blog</a></li>
                    <li><a class="" href="<?= base_url('developers') ?>">Developer Docs</a></li>
              <?php if (session('uid')) {?>
              <li><a class="getstarted scrollto" href="<?= base_url('user/dashboard') ?>">Dashboard</a></li>
               
               
                       <?php }else{ ?>
                    
                    
                    <li><a class="getstarted scrollto" href="<?= base_url('sign-in') ?>">Login</a></li>
                 
                    <?php } ?>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>
        </div>
    </header>
    <?= view($view); ?>
    <footer id="footer" class="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-5 col-md-12 footer-info">
                        <a href="<?= base_url(); ?>" class="logo d-flex align-items-center">
                            <span><?= site_config('site_name'); ?></span>
                        </a>

                        <p><?= site_config('site_description') ?></p>
                        <div class="social-links mt-3">
                            <a href="<?= site_config('social_twitter_link') ?>" class="twitter"><i class="bi bi-twitter"></i></a>
                            <a href="<?= site_config('social_facebook_link') ?>" class="facebook"><i class="bi bi-facebook"></i></a>
                            <a href="<?= site_config('social_instagram_link') ?>" class="instagram"><i class="bi bi-instagram"></i></a>
                            <a href="<?= site_config('social_github_link') ?>" class="github"><i class="bi bi-github"></i></a>
                            <a href="<?= site_config('social_youtube_link') ?>" class="youtube"><i class="bi bi-youtube"></i></a>
                        </div>
                    </div>

<?php echo htmlspecialchars_decode(get_option('embed_javascript', ''), ENT_QUOTES); ?>



                    <div class="col-lg-2 col-6 footer-links">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><i class="bi bi-chevron-right"></i> <a href="<?= base_url("#about") ?>">About Us</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="<?= base_url("terms-condition") ?>">Terms & Conditions</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="<?= base_url("privacy-policy") ?>">Privacy & Policy</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-2 col-6 footer-links">
                        <h4>Help</h4>
                        <ul>
                            <li><i class="bi bi-chevron-right"></i> <a href="<?= base_url('#faq') ?>">FAQs</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="<?= base_url('#pricing') ?>">Pricing</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="<?= base_url('#services') ?>">Our Services</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
                        <h4>Contact Us</h4>
                        <p>
                            <?= site_config('address') ?>
                            <strong>Phone:</strong> <?= site_config('contact_tel') ?>5<br>
                            <strong>Email:</strong> <?= site_config('contact_email') ?><br>
                        </p>

                    </div>

                </div>
            </div>
        </div>

        <div class="container">
            <div class="copyright"><?= site_config('copy_right_content') ?>
                @<script>
                    document.write(new Date().getFullYear())
                </script>
            </div>

        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <?= script_asset('frontend/vendor/purecounter/purecounter_vanilla.js') ?>
    <?= script_asset('frontend/vendor/aos/aos.js') ?>
    <?= script_asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>
    <?= script_asset('frontend/vendor/glightbox/js/glightbox.min.js') ?>
    <?= script_asset('frontend/vendor/isotope-layout/isotope.pkgd.min.js') ?>
    <?= script_asset('frontend/vendor/swiper/swiper-bundle.min.js') ?>
    <?= script_asset('frontend/vendor/php-email-form/validate.js') ?>
    <?= script_asset('frontend/js/main.js') ?>


    <?php if (get_option('enable_notification_popup') == 1 && get_cookie('home_popup') != 1) : ?>
        <?php set_cookie("home_popup", "1", 180); ?>
        <div class="modal zoom-in" id="notification" tabindex="-1" aria-labelledby="notificationLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="notificationLabel">
                            <i class="bi bi-megaphone announcement-icon"></i>
                            <span class="announcement-text">Announcement</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?= get_option('notification_popup_content') ?>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    var notificationModal = new bootstrap.Modal(document.getElementById('notification'));
                    notificationModal.show();
                }, 500);
            });
        </script>
    <?php endif; ?>


    
</body>

</html>