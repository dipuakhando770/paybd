<nav class="navbar navbar-expand-lg main-navbar sticky">
   <div class="form-inline me-auto">
      <ul class="navbar-nav mr-3">
         <li><a href="#" data-bs-toggle="sidebar" class="nav-link nav-link-lg collapse-btn"> <i data-feather="menu"></i></a></li>
      </ul>
   </div>
   <ul class="navbar-nav navbar-right">
      <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
            <i data-feather="maximize"></i>
         </a></li>
      <li><a href="#" class="nav-link nav-link-lg theme-btn">
            <i data-feather="sun" class="sun" onclick="toggleTheme()"></i>
            <i data-feather="moon" class="moon d-none" onclick="toggleTheme()"></i>
         </a></li>
      <li class="dropdown dropdown-list-toggle"><a href="#" data-bs-toggle="dropdown" class="nav-link nav-link-lg message-toggle"><i data-feather="bell" class="mailAnim"></i>
            <span class="badge headerBadge1">
            </span> </a>
         <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
            <div class="dropdown-header">
               Notifications
               <div class="float-right">
                  <a href="#">Mark All As Read</a>
               </div>
            </div>
            <div class="dropdown-list-content dropdown-list-message">
               <a href="#" class="dropdown-item">
                  <span class="dropdown-item-desc">
                     <span class="message-user">No Notification found</span>
                  </span>
               </a>

            </div>
            <div class="dropdown-footer text-center">
               <a href="#">View All <i class="fas fa-chevron-right"></i></a>
            </div>
         </div>
      </li>

      <li class="dropdown"><a href="#" data-bs-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image" src="<?= get_avatar('admin') ?>" class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>
         <div class="dropdown-menu dropdown-menu-right pullDown">
            <div class="dropdown-title">Hello <?= current_admin('first_name'); ?></div>
            <a href="<?= admin_url('profile') ?>" class="dropdown-item has-icon">
               <i class="far fa-user"></i> Profile
            </a>
            <div class="dropdown-divider"></div>
            <a href="<?= admin_url("logout") ?>" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i>
               Logout
            </a>
         </div>
      </li>
   </ul>
</nav>