
<div class="row">
    <h4>User Addons</h4>
  <?php 
    echo show_page_header('addons', ['page-options' => 'add-new', 'page-options-type' => 'ajax-modal']);
    if(!empty($items)){
  ?>
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="card-header">
            <h3 class="card-title"><?=lang("Lists")?></h3>
            <div class="card-options">
              <?php echo show_bulk_btn_action('user-addon'); ?>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover table-bordered table-vcenter card-table">
              <?php echo render_table_thead($columns); ?>
              <tbody class="sortable">
                <?php if (!empty($items)) {
                  $i = 0;
                  foreach ($items as $key => $item) {
                    $item = (array)$item;
                    $i++;
                    $item_checkbox      = show_item_check_box('check_item', $item['id']);
                    $show_item_buttons  = show_item_button_action('user-addon', $item['id']);

                ?>
                  <tr class="tr_<?php echo esc($item['id']); ?>" data-code="<?php echo esc($item['id']); ?>">
                    <th class="text-center"><?php echo $item_checkbox; ?></th>
                    <td class="text-center text-muted w-5p"><?=$i?></td>
                    <td class="w-5p"><?= $item['name']; ?></td>
                    <td class="text-center w-5p"><?= currency_format($item['price']); ?></td>
                    <td class="text-center w-5p"><?php echo $item['version']; ?></td>
                    <td class="text-center w-5p"><?php echo $show_item_buttons; ?></td>
                  </tr>
                <?php }}?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?php }?>
</div>


<style>#upload-btn{display: none;} #drop-area {border: 2px dashed #ccc; border-radius: 8px; padding: 20px; text-align: center; cursor: pointer; } #drop-area.highlight {border-color: #007bff; } #file-input {display: none; }</style>
<?=form_open('','class="actionForm" data-redirect= "'.admin_url('addons').'" ')?>

<div class="container mt-5">
    <div id="drop-area" class="mb-3">
        <div class="settings">
            <input type="text" name="file" class="d-none" value="" >
            <span class="text-center">
                <label for="file-input" class="btn p-2 m-4 border border-primary">Upload a ZIP File</label>
                <input class="settings_fileupload" id="file-input" data-type="zip" type="file" name="files[]" onchange="myF()">
                <button id="upload-btn" class="btn btn-success" type="submit">Extract File</button>
    
            </span>
        </div>
    </div>
</div>
<?=form_close();?>
<script>
    function myF(){
        const uploadBtn = document.getElementById('upload-btn');
        uploadBtn.style.display = 'inline';
    }
</script>

<div class="bg-secondary rounded h-100 p-4">
    <div class="row">
        <?php foreach($addons as $addon): ?>
            <div class="col-md-4 bg-dark p-4">
                <div class="form-check form-switch">
                    <label class="form-check-label" for="<?=$addon?>">Status</label>
                    <?php
                     $id = get_option('enable_'.lcfirst($addon), '0');
                     $item_status        = show_item_status('addons', $addon,$id , 'switch');
                     echo $item_status;                     
                    ?>
                </div>
                <div>
                    <?php
                        echo get_addon_details($addon);
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>