<!DOCTYPE html>
<html lang="en">
<head>
  <title><?=!empty($title)?$title.'-'.site_config("site_title","author"):site_config("site_title","author")?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="author" content="<?=site_config('site_description','author')?>">
  <meta name="description" content="<?=site_config('site_description','Site desc')?>">
  <meta name="theme-color" content="#303030">
  <meta property="og:url" content="<?= current_url(true)?>">
  <meta property="og:site_name" content="<?=site_config("site_name","My Site")?>">
  <meta property="og:locale" content="en_US">
  <link rel="shortcut icon" href="<?=get_logo(true);?>">


  <?=link_asset('blithe/css/app.min.css');?>
  <?=link_asset('blithe/css/style.css');?>
  <?= link_asset('js/jquery-toast/css/jquery.toast.css') ?>
  <?=link_asset('js/select2/css/select2.min.css')?>

  <?=link_asset('blithe/css/components.css');?>
  <?=link_asset('blithe/css/jqvmap.min.css');?>
  <?=script_asset("blithe/js/app.min.js");?>
  <script type="text/javascript">
      var token = '<?= csrf_hash() ?>',PATH = '<?=base_url()?>',user='user';
  </script>


</head>

<body>
  <div id="loader"class="show"></div>
  <div id="page-overlay" class="visible incoming"> <div class="loader-wrapper-outer"> <div class="loader-wrapper-inner"> <div class="lds-double-ring"> <div></div> <div></div> <div> <div></div> </div> <div> <div></div> </div> </div> </div> </div> </div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <?php include 'elements/navbar.php'; ?>
      <?php include 'elements/sidebar.php'; ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          
          <?=view($view);?>
        </section>
        
      </div>
      
    </div>
  </div>
  <!-- General JS Scripts -->
  <?=script_asset("blithe/js/scripts.js");?>
  

    <!--my-->
    <?=script_asset('js/notify.min.js')?>
    <?=script_asset('js/tinymce/tinymce.min.js')?>
    <?=script_asset('js/jquery-toast/js/jquery.toast.js')?>
    <?=script_asset('js/process2.js')?>
    <?=script_asset('js/general.js')?>
    <?=script_asset('js/select2/js/select2.full.min.js')?>

    

    <?=script_asset('js/admin.js')?>
    <?=script_asset('js/jquery-upload/js/vendor/jquery.ui.widget.js')?>
    <?=script_asset('js/jquery-upload/js/jquery.iframe-transport.js')?>
    <?=script_asset('js/jquery-upload/js/jquery.fileupload.js')?>
    
    <?php if ($msg = session()->getFlashdata('message')) : ?>
        <script type="text/javascript">
            notify('<?= esc($msg['message']) ?>', '<?= esc($msg['status']) ?>');
        </script>
    <?php endif; ?>

    <div id="modal-ajax" class="modal fade" ></div>
    
    <?=script_asset('js/jquery-ui.min.js')?>
    <?=script_asset('js/blithe.js')?>

    
    <?php if (get_option('enable_panel_notification_popup') == 1 && get_cookie('panel_popup') != 1) : ?>
        <?php set_cookie("panel_popup", "1", 180); ?>
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
                        <?= get_option('notification_popup_panel_content') ?>
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

     <?php echo htmlspecialchars_decode(get_option('embed_head_javascript', ''), ENT_QUOTES); ?>

</body>

</html>
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

.whatsapp-button i {
    font-size: 32px; /* Adjust this value to make the icon bigger or smaller */
}

</style>