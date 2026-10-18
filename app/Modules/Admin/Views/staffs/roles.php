<div class="row justify-content-between">
    <div class="col-6">
        <h1 class="page-title">
            <span class=""></span> Roles & permission  
        </h1>
    </div>
    <div class="col-6">
        <div class="d-flex float-end">
            <a href="<?=admin_url('staffs/role_permision/add');?>" class="ml-auto btn btn-outline-primary ajaxModal">
                <span class="fas fa-plus"></span>
                Add new
            </a>
        </div>
    </div>
</div>

<div class="card">
  <div class=""  >
    <table class="table table-bordered table-vcenter card-table">
      <thead>
        <tr>
          <th>sl</th>
          <th>Name</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($items)) {
          
          $i = 0;
          foreach ($items as $key => $item) {
            $i++;
        ?>
          <tr  class="tr_<?php echo esc($item->id); ?>">
            
            <td class="w-15p"><?php echo $i; ?></td>
            <td class="w-5p"><?php echo $item->name; ?></td>
            <td class="w-5p">
              <a href="<?=admin_url('staffs/role_permision/update/'.$item->id);?>" class="ajaxModal" data-confirm_ms=""><i class="fas fa-edit"></i> Edit</a>

              <a href="<?=admin_url('staffs/role_permision/delete/'.$item->id);?>" class=" ajaxDeleteItem p-2" data-confirm_ms="delete this item"><i class="fas fa-trash"></i>  Delete</a>

            </td>
          </tr>
        <?php }}?>
      </tbody>
    </table>
  </div>
</div>