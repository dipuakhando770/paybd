<?php
$setting_sidebar = [
  'general_setting' => [
    'name' => 'General setting', 'icon' => 'fa fa-cog',        'area_title' => true,  'route-name' => '#',
    'elements' => [
      'website_setting' => ['name' => 'Website setting', 'icon' => 'fa fa-globe',       'area_title' => false, 'route-name' => 'website_setting'],
      'website_logo'    => ['name' => 'Website logo',    'icon' => 'fa fa-image',       'area_title' => false, 'route-name' => 'website_logo'],
      'default'         => ['name' => 'Default setting', 'icon' => 'fa fa-box',         'area_title' => false, 'route-name' => 'default'],
      'affiliate'         => ['name' => 'Affiliate setting', 'icon' => 'fa fa-box',     'area_title' => false, 'route-name' => 'affiliates'],
      'currency'        => ['name' => 'Currency',        'icon' => 'fa fa-dollar-sign', 'area_title' => false, 'route-name' => 'currency'],
      'cookie_policy'   => ['name' => 'Cookie policy',   'icon' => 'fa fa-bookmark',    'area_title' => false, 'route-name' => 'cookie_policy'],
      'terms_policy'    => ['name' => 'Terms policy',    'icon' => 'fa fa-award',       'area_title' => false, 'route-name' => 'terms_policy'],
      'other'           => ['name' => 'Other',           'icon' => 'fa fa-cog',     'area_title' => false, 'route-name' => 'other'],
      'dev_page'        => ['name' => 'Developer Page', 'icon' => 'fa fa-code', 'area_title' => false, 'route-name' => 'dev_page'],
            'home_page'       => ['name' => 'Home Page', 'icon' => 'fa fa-home', 'area_title' => false, 'route-name' => 'home_page'],
    ],
  ],
  'email' => [
    'name'     => 'Email', 'icon' => 'fa fa-envelope-open', 'area_title' => true,  'route-name' => '#',
    'elements' => [
      'email_setting'   => ['name' => 'Email setting',   'icon' => 'fa fa-envelope-open',        'area_title' => false, 'route-name' => 'email_setting'],
      'email_template'  => ['name' => 'Email template',  'icon' => 'fa fa-box',   'area_title' => false, 'route-name' => 'email_template'],
    ],
  ],
  
];

$xhtml = '<div class="card sidebar p-2">';
$i = 0;
foreach ($setting_sidebar as $key => $item) {
  $xhtml .= sprintf(
    '
        <div class="list-group list-group-transparent mb-1 mt-2 ">
          <h5><span class="icon mr-3"><i class="%s"></i></span>%s</h5>
        </div>',
    $item['icon'],
    $item['name']
  );
  if (!empty($item['elements'])) {
    $xhtml_child = '<div class="list-group list-group-transparent mb-0">';
    foreach ($item['elements'] as $element) {
      $link = admin_url('settings/' . $element['route-name']);
      $class_active = ($element['route-name'] == segment(3)) ? 'active' : '';
      $xhtml_child .= sprintf(
        '<a href="%s" class="list-group-item list-group-item-action %s"><span class="icon mr-3"><i class="%s"></i></span>%s</a>',
        $link,
        $class_active,
        $element['icon'],
        $element['name']
      );
    }
    $xhtml_child  .= '</div>';
  }
  $i++;
  $xhtml .= $xhtml_child;
}
$xhtml .= '</div>';
echo $xhtml;
