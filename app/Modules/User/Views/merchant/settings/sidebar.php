<ul class="nav mb-3 setting-sidebar" role="tablist">
<?php
  $xhtml = '';
  $form_items_payment = [
    'other' => 'Payment Settings',
  ];
  $items_payment = array_combine(array_column($items_payment, 'type'), array_column($items_payment, 'name'));
    
    if (!empty($items_payment)) {
      foreach ($items_payment as $key => $item) {
        $link = user_url('user-settings/' . $key);
        $class_active = ($key == $tab ) ? 'active' : '';
        $xhtml .= sprintf(
          '<li class="nav-item"><a href="%s" class="nav-link %s"><span class="icon mr-3"></span>%s</a></li>', $link, $class_active, $item
        );
      }
    }
  echo $xhtml;
?>
</ul>
