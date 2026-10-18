<div class="main-sidebar sidebar-style-2">
   <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
         <a href="<?= user_url(); ?>"> <img alt="image" src="<?= get_logo() ?>" class="header-logo" />
         </a>
      </div>
      <div class="sidebar-user">
         <div class="sidebar-user-picture">
            <img alt="image" src="<?= get_avatar('user') ?>">
         </div>
         <div class="sidebar-user-details">
            <div class="user-name"><?= current_user('first_name') ?></div>
            <div class="user-role">User</div>
            <div class="sidebar-userpic-btn">
               <a href="<?= user_url('profile') ?>" data-bs-toggle="tooltip" title="Profile">
                  <i data-feather="user"></i>
               </a>
               <a href="<?= user_url('tickets') ?>" data-bs-toggle="tooltip" title="Tickets">
                  <i data-feather="mail"></i>
               </a>

               <a href="<?= user_url('logout') ?>" data-bs-toggle="tooltip" title="Log Out">
                  <i data-feather="log-out"></i>
               </a>
            </div>
         </div>
      </div>
      <ul class="sidebar-menu">
         <li class="menu-header">Main</li>
         <li class="dropdown">
            <a href="<?= user_url('dashboard') ?>" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
         </li>
         <li class="dropdown">
            <a href="<?= user_url('add_funds') ?>" class="nav-link ajaxModal"><i data-feather="plus-circle"></i><span>Add Funds</span></a>
         </li>

         <li class="dropdown">
            <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="briefcase"></i><span>Transactions</span></a>
            <ul class="dropdown-menu">
               <li><a class="nav-link" href="<?= user_url('transactions') ?>">Transactions</a></li>
               <li><a class="nav-link" href="<?= user_url('bank_transactions') ?>">Bank Transactions</a></li>
            </ul>
         </li>
         <li class="dropdown">
            <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="file-text"></i><span>Invoice</span></a>
            <ul class="dropdown-menu">
               <li><a class="nav-link" href="<?= user_url('invoice') ?>">Invoices</a></li>
               <li><a class="nav-link ajaxModal" href="<?= user_url('invoice/update') ?>">Add Invoice</a></li>
            </ul>
         </li>
         <li class="dropdown">
            <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="hard-drive"></i><span>Data</span></a>
            <ul class="dropdown-menu">
               <li><a class="nav-link" href="<?= user_url('stored-data') ?>">Stored Data</a></li>
               <li><a class="nav-link ajaxModal" href="<?= user_url('transactions/add-data') ?>">Add Data</a></li>
            </ul>
         </li>
         <li class="dropdown">
            <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="pie-chart"></i><span>Settings</span></a>
            <ul class="dropdown-menu">
               <li><a class="nav-link" href="<?= user_url('brands') ?>">Brands</a></li>
               <li><a class="nav-link" href="<?= user_url('devices') ?>">Devices</a></li>
               <li><a class="nav-link" href="<?= user_url('user-settings/wallets') ?>">Wallets</a></li>
            </ul>
         </li>

         <li class="menu-header">Others</li>
         <li class="dropdown">
            <a href="<?= user_url('affiliates') ?>" class="nav-link"><i data-feather="send"></i><span>Affiliates</span></a>
         </li>
         <li class="dropdown">
            <a href="<?= user_url('plans') ?>" class="nav-link"><i data-feather="gift"></i><span>Plans</span></a>
         </li>

         <li class="dropdown">
            <a href="<?= base_url('public/assets/downloads/nagorikpay.apk') ?>" class="nav-link"><i data-feather="smartphone"></i><span>Android App</span></a>
         </li>
         <li>
          <a href="<?=base_url()?>" target="_blank"><i data-feather="globe"></i>Main Site</a>
        </li>
         <li class="dropdown mb-4">
            <a href="<?= base_url('developers') ?>" class="nav-link"><i data-feather="package"></i><span>Developer Tool</span></a>
         </li>
         

      </ul>
   </aside>
</div>