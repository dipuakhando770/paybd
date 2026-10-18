<div class="dropdown-menu dropdown-menu-end bg-secondary mt-4 border-0 rounded-0 rounded-bottom m-0 notification-container show" data-bs-popper="static">
    <div class="p-1">
        <ul class=""> <div class="col-md-12 data-empty text-center">
        <li class="text-info text-center border-bottom">Your Search Resuls...</li>
         <?php
            if (!empty($items)) {
               foreach ($items as $item) {
                  ?>
         <li>
            <div class="timeline-panel">
               <div class="me-2">
                  <span class="badge badge-info"><?=$item['from']?></span>
               </div>
               <div class="media-body">
                  <h6 class="mb-1"><a href="<?=$item['link']?>"><?=$item['search']?></a></h6>
                  <small class="d-block"><?=$item['created']?></small>
               </div>
            </div>
         </li>
                  <?php 
               }
            }else{
               echo show_empty_item();
            }

         ?> 
        </ul>
    </div>
</div>