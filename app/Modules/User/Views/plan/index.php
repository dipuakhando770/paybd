<div class="card overflow-hidden">
  <!-- Pricing Plans -->
  <div class="pb-sm-5 pb-2 rounded-top">
    <div class="container py-5">

      <div class="row mx-0 gy-3 px-lg-2">
        <?php if (!empty($items)) :
          foreach ($items as $item) :
        ?>
            <div class="col-md-4 mb-md-0 mb-4">
              <div class="card <?= (get_active_plan() && get_active_plan()->plan_id == $item['id']) ? 'border-success border-4 shadow-primary active-plan' : 'border shadow-none' ?>">
                <div class="card-body position-relative">

                  <h3 class="card-title text-center text-capitalize mb-1"><?= $item['name'] ?></h3>
                  <p class="text-center"><?= $item['description'] ?></p>
                  <div class="text-center">
                    <div class="d-flex justify-content-center">
                      <sup class="h6 pricing-currency mt-3 mb-0 me-1 "><?= get_option('currency_symbol') ?></sup>
                      <h1 class="price-toggle price-yearly display-4 mb-0"><?= currency_format($item['final_price']) ?></h1>
                      <sub class="h6 text-muted pricing-duration mt-auto mb-2 fw-normal">/<?= duration_type($item['name'], $item['duration_type'], $item['duration']) ?></sub>
                    </div>
                  </div>

                  <ul class="my-4 list-unstyled">

                    <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span>
                      <?= plan_message('brand', $item['brand']) ?>
                    </li>
                    <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span>
                      <?= plan_message('device', $item['device']) ?>
                    </li>
                    <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary "><i class="bx bx-check bx-xs"></i></span>
                      <?= plan_message('transaction', $item['transaction']) ?>
                    </li>
                  </ul>
                  <?= plan_button($item['id']); ?>
                </div>
              </div>
            </div>
        <?php
          endforeach;
        endif;
        ?>
      </div>
    </div>
  </div>
  <!--/ Pricing Plans -->
</div>

<script>
  function updateCountdown(targetTime, countdownElement) {
    const currentTime = new Date();
    const timeDifference = targetTime - currentTime;
    if (timeDifference <= 0) {
      countdownElement.text("Expired");
    } else {
      const days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
      const hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);
      countdownElement.text(`${days}d ${hours}h ${minutes}m ${seconds}s`);
    }
  }

  function updateCountdowns() {
    $(".countdown").each(function(index) {
      const targetTimeStr = $(this).siblings(".getExpire").text();
      const targetTime = new Date(targetTimeStr);
      updateCountdown(targetTime, $(this));
    });
  }
  setInterval(updateCountdowns, 1000);
  updateCountdowns();
</script>