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
   <link rel="icon" type="image/png" href="<?= get_logo(true) ?>" />
   <link rel="apple-touch-icon" type="image/png" sizes="76x76" href="<?= get_logo() ?>" />
   <link rel="mask-icon" href="<?= get_logo() ?>" color="#5bbad5" />
   <link rel="canonical" href="<?= base_url() ?>">

   <?= link_asset('blithe/css/app.min.css'); ?>
   <?= link_asset('blithe/css/style.css'); ?>
   <?= link_asset('blithe/css/components.css'); ?>
   <?= link_asset('js/jquery-toast/css/jquery.toast.css') ?>

</head>

<body>
   <div id="loader"></div>

   <?= view($view); ?>
   <?= script_asset("blithe/js/app.min.js"); ?>
   <?= script_asset("blithe/js/scripts.js"); ?>

   <?= script_asset('js/jquery-toast/js/jquery.toast.js') ?>
   <?= script_asset('js/process.js') ?>
   <?= script_asset('js/general.js') ?>
   <?php if ($msg = session()->getFlashdata('message')) : ?>
      <script type="text/javascript">
         notify('<?= esc($msg['message']) ?>', '<?= esc($msg['status']) ?>');
      </script>
   <?php endif; ?>
</body>

</html>