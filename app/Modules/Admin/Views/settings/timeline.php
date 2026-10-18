
<div class="row">
  <!-- User Sidebar -->
  <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
    <!-- User Card -->
    <div class="card mb-4">
      
      <div class="card-body">
        <div class="user-avatar-section">
          <div class=" d-flex align-items-center flex-column">
            <img class="img-fluid rounded my-4" src="<?=get_avatar('admin',$item->id);?>" height="110" width="110" alt="User avatar">
            <div class="user-info text-center">
              <h4 class="mb-2"><?=$item->first_name?></h4>
            </div>
          </div>
        </div>
        
        <h5 class="pb-2 border-bottom mb-4">Details</h5>
        <div class="info-container">
          <ul class="list-unstyled">
            <li class="mb-3">
              <span class="fw-medium me-2">Name:</span>
              <span><?=$item->first_name.' '.$item->last_name;?></span>
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Email:</span>
              <span><?=$item->email?></span>
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Status:</span>
              <?=show_item_status('','',$item->status)?>
            </li>
            <li class="mb-3">
              <span class="fw-medium me-2">Role:</span>
              <span><?=$item->name??'Not Detected';?></span>
            </li>

          </ul>
        </div>
      </div>
    </div>
  </div>
  <!--/ User Sidebar -->

  <!-- User Content -->
  <div class="col-xl-8 col-lg-7 col-md-7 order-1">
    <div class="card">
      <div  class="card-header mb-4"> 
          <?=form_open('',['class'=>"input-group"])?>
          <input type="date" class="form-control" name="start_date" value="<?=post('start_date')??'';?>">
          <span class="input-group-text">To</span>
          <input type="date" class="form-control" name="end_date" value="<?=post('end_date')??'';?>">
          <input type="submit" class="btn btn-success" value="Search">
          <?=form_close()?>
      </div>
      <div class="card-body">
        <ul class="timeline">
          <?php 
            foreach($items as $logs){
          ?>
          <li class="timeline-item timeline-item-transparent">
            <span class="timeline-point-wrapper"><span class="timeline-point timeline-point-primary"></span></span>
            <div class="timeline-event">
              <div class="timeline-header border-bottom mb-3">
                <h6 class="mb-0"><?=shorten_string($logs->activity)?></h6>
                <span class="text-muted"><?=show_item_datetime($logs->created_at,'short')?></span>
              </div>
              <div class="d-flex justify-content-between flex-wrap mb-2">
                <div>
                  <span>User Activity: </span>
                  <i class="bx bx-right-arrow-alt scaleX-n1-rtl mx-3"></i>
                  <span><?=$logs->activity;?></span>
                </div>
                <div>
                  <span class="text-muted"><?= date("h:i A", strtotime($logs->created_at)) ?></span>
                </div>
              </div>
              <?=getAnchor($logs->activity);?>
              
            </div>
          </li>

          <?php 
            }
          ?>
        
          <li class="timeline-end-indicator">
            <i class="bx bx-check-circle"></i>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <!--/ User Content -->
</div>



<div class="card-body">
<button class="btn btn-primary" id="shepherd-example">
    Start tour
</button>
</div>

<?=link_asset('blithe/vendor/libs/shepherd/shepherd.css')?>
<?=script_asset('blithe/vendor/libs/shepherd/shepherd.js')?>


<script>

"use strict";
!function() {
    var t = document.querySelector("#shepherd-example");
    t && (t.onclick = function() {
        var t, e, a = new Shepherd.Tour({
            defaultStepOptions: {
                scrollTo: !1,
                cancelIcon: {
                    enabled: !0
                }
            },
            useModalOverlay: !0
        });
        t = "btn btn-sm btn-label-secondary md-btn-flat",
        e = "btn btn-sm btn-primary btn-next",
        (a = a).addStep({
            title: "Navbar",
            text: "This is your navbar",
            attachTo: {
                element: ".navbar",
                on: "bottom"
            },
            buttons: [{
                action: a.cancel,
                classes: t,
                text: "Skip"
            }, {
                text: "Next",
                classes: e,
                action: a.next
            }]
        }),
        a.addStep({
            title: "Card",
            text: "This is a card",
            attachTo: {
                element: ".tour-card",
                on: "top"
            },
            buttons: [{
                text: "Skip",
                classes: t,
                action: a.cancel
            }, {
                text: "Back",
                classes: t,
                action: a.back
            }, {
                text: "Next",
                classes: e,
                action: a.next
            }]
        }),
        a.addStep({
            title: "Footer",
            text: "This is the Footer",
            attachTo: {
                element: ".footer",
                on: "top"
            },
            buttons: [{
                text: "Skip",
                classes: t,
                action: a.cancel
            }, {
                text: "Back",
                classes: t,
                action: a.back
            }, {
                text: "Next",
                classes: e,
                action: a.next
            }]
        }),
        a.addStep({
            title: "Upgrade",
            text: "Click here to upgrade plan",
            attachTo: {
                element: ".footer-link",
                on: "top"
            },
            buttons: [{
                text: "Back",
                classes: t,
                action: a.back
            }, {
                text: "Finish",
                classes: e,
                action: a.cancel
            }]
        }),
        a.start()
    }
    );
    t = document.querySelector("#shepherd-docs-example");
    t && (t.onclick = function() {
        var t, e, a = new Shepherd.Tour({
            defaultStepOptions: {
                scrollTo: !1,
                cancelIcon: {
                    enabled: !0
                }
            },
            useModalOverlay: !0
        });
        t = "btn btn-sm btn-label-secondary md-btn-flat",
        e = "btn btn-sm btn-primary btn-next",
        (a = a).addStep({
            title: "Navbar",
            text: "This is your navbar",
            attachTo: {
                element: ".navbar",
                on: "bottom"
            },
            buttons: [{
                action: a.cancel,
                classes: t,
                text: "Skip"
            }, {
                text: "Next",
                classes: e,
                action: a.next
            }]
        }),
        a.addStep({
            title: "Footer",
            text: "This is the Footer",
            attachTo: {
                element: ".footer",
                on: "top"
            },
            buttons: [{
                text: "Skip",
                classes: t,
                action: a.cancel
            }, {
                text: "Back",
                classes: t,
                action: a.back
            }, {
                text: "Next",
                classes: e,
                action: a.next
            }]
        }),
        a.addStep({
            title: "Social Link",
            text: "Click here share on social media",
            attachTo: {
                element: ".footer-link",
                on: "top"
            },
            buttons: [{
                text: "Back",
                classes: t,
                action: a.back
            }, {
                text: "Finish",
                classes: e,
                action: a.cancel
            }]
        }),
        a.start()
    }
    )
}();

</script>