<!doctype html>
<html lang="en">

<head>
    <title><?= !empty($title) ? $title . '-' . site_config("site_title", "author") : site_config("site_title", "author") ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="author" content="<?= site_config('site_description', 'author') ?>">
    <meta name="description" content="<?= site_config('site_description', 'Site desc') ?>">
    <meta name="theme-color" content="#303030">
    <meta name="keywords" content="<?= site_config('site_keywords', "keywords") ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= !empty($title) ? $title . '-' . site_config("site_title", "author") : site_config("site_title", "author") ?>">
    <meta property="og:description" content="<?= site_config('site_description', 'Site desc') ?>">
    <meta property="og:image" content="<?= get_logo() ?>">
    <meta property="og:url" content="<?= current_url(true) ?>">
    <meta property="og:site_name" content="<?= site_config("site_name", "My Site") ?>">
    <meta property="og:locale" content="en_US">
    <meta name="twitter:card" content="<?= get_logo() ?>">
    <meta name="twitter:url" content="<?= current_url(true) ?>">
    <meta name="twitter:title" content="<?= !empty($title) ? $title . '-' . site_config("site_title", "author") : site_config("site_title", "author") ?>">
    <meta name="twitter:image" content="<?= get_logo() ?>">
    <link rel="icon" type="image/png" href="<?= get_logo() ?>" />
    <link rel="apple-touch-icon" type="image/png" sizes="76x76" href="<?= get_logo() ?>" />
    <link rel="mask-icon" href="<?= get_logo() ?>" color="#5bbad5" />
    <link rel="canonical" href="<?= base_url() ?>">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,900" rel="stylesheet">
    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="<?= base_url('public/assets/css/404.css') ?>" />


</head>

<body>
    <div id="notfound">
        <div class="notfound">
            <div class="notfound-404">
                <h1>Oops!</h1>
            </div>
            <h2>404 - Page not found</h2>
            <p>The page you are looking for might have been removed had its name changed or is temporarily unavailable.</p>
            <a href="<?= base_url() ?>">Go To Homepage</a>
        </div>
    </div>
</body>

</html>