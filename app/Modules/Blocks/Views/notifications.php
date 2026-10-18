 <?php 
   if (!empty($notItems)) {
      foreach ($notItems as $item) {
?>

<li class="list-group-item list-group-item-action dropdown-notifications-item notification_single"data-type='single' data-id='<?=$item['id']?>'>
   <div class="d-flex">
      <div class="flex-shrink-0 me-3">
         <div class="avatar">
            <img src="<?=get_avatar('user')?>" alt class="w-px-40 h-auto rounded-circle" />
         </div>
      </div>
      <div class="flex-grow-1">
         <h6 class="mb-1"><?=shorten_string($item['message'],5)?>🎉</h6>
         <p class="mb-0"> <?=$item['message']?> </p>
         <small class="text-muted"><?=time_ago($item['created_at'])?></small>
      </div>
      
   </div>
</li>

<?php 
}
}else{
   echo show_empty_item();
   echo "<hr class='dropdown-divider'>";
}
?>

