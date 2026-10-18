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

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <style>
        html,
        body {
            padding: 0;
            margin: 0;
            width: 100%;
            height: 100%;
        }

        * {
            box-sizing: border-box;
        }

        body {
            text-align: center;
            padding: 0;
            background: #d6433b;
            color: #fff;
            font-family: 'Open Sans', sans-serif;
        }

        h1 {
            font-size: 50px;
            font-weight: 100;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        article {
            display: block;
            width: 700px;
            padding: 50px;
            margin: 0 auto;
        }

        a {
            color: #fff;
            font-weight: bold;
        }

        a:hover {
            text-decoration: none;
        }

        svg {
            width: 75px;
            margin-top: 1em;
        }
    </style>
</head>

<body>
    <article>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 202.24 202.24">
            <defs>
                <style>
                    .cls-1 {
                        fill: #fff;
                    }
                </style>
            </defs>
            <title>Asset 3</title>
            <g id="Layer_2" data-name="Layer 2">
                <g id="Capa_1" data-name="Capa 1">
                    <path class="cls-1" d="M101.12,0A101.12,101.12,0,1,0,202.24,101.12,101.12,101.12,0,0,0,101.12,0ZM159,148.76H43.28a11.57,11.57,0,0,1-10-17.34L91.09,31.16a11.57,11.57,0,0,1,20.06,0L169,131.43a11.57,11.57,0,0,1-10,17.34Z" />
                    <path class="cls-1" d="M101.12,36.93h0L43.27,137.21H159L101.13,36.94Zm0,88.7a7.71,7.71,0,1,1,7.71-7.71A7.71,7.71,0,0,1,101.12,125.63Zm7.71-50.13a7.56,7.56,0,0,1-.11,1.3l-3.8,22.49a3.86,3.86,0,0,1-7.61,0l-3.8-22.49a8,8,0,0,1-.11-1.3,7.71,7.71,0,1,1,15.43,0Z" />
                </g>
            </g>
        </svg>
        <h1>We’ll be back soon!</h1>
        <div>
            <p>Sorry for the inconvenience. We’re performing some maintenance at the moment. We’ll be back up shortly!</p>
        </div>
    </article>
</body>

</html>