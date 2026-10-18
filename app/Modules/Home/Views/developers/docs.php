<?php
define("PAYMENT_URL", getenv('PAYMENT_URL'));
?>
<style>
   .table thead th {
      border: 1px solid #dddddd;
      white-space: nowrap;
   }

   .table-striped>tbody>tr:nth-of-type(2n+1)>* {
      border: 1px solid #ddd;
      --bs-table-accent-bg: #FFF;
      padding: 17px !important;
      font-size: 15px;
   }

   thead,
   tbody,
   tfoot,
   tr,
   td,
   th {
      border: 1px solid #ddd;
      --bs-table-accent-bg: #FFF;
      padding: 17px !important;
      font-size: 15px;
   }

   .table thead th {
      border: 1px solid #dddddd;
      white-space: nowrap;
      padding: 17px !important;
      font-size: 16px;
   }

   #ertyertyerty {
      color: #FFFFFF;
      text-decoration: none;
   }

   #ertyertyerty:hover {
      text-decoration: underline;
   }

   .docs-wrapper {
      overflow: hidden;
   }
</style>


<div class="docs-wrapper">
   <div id="docs-sidebar" class="docs-sidebar">
      <div class="top-search-box d-lg-none p-3">
         <form class="search-form">
            <input type="text" placeholder="Search the docs..." name="search" class="form-control search-input">
            <button type="submit" class="btn search-btn" value="Search"><i class="fas fa-search"></i></button>
         </form>
      </div>
      <nav id="docs-nav" class="docs-nav navbar">
         <ul class="section-items list-unstyled nav flex-column pb-3">
            <li class="nav-item section-title"><a class="nav-link scrollto active" href="#section-1"><span class="theme-icon-holder me-2"><i class="fas fa-map-signs"></i></span>Introduction</a></li>

            <li class="nav-item section-title mt-3"><a class="nav-link scrollto" href="#section-2"><span class="theme-icon-holder me-2"><i class="fas fa-key"></i></span>APIs</a></li>
            <li class="nav-item"><a class="nav-link scrollto" href="#section-2">API Introduction</a></li>
            <li class="nav-item"><a class="nav-link scrollto" href="#item-2-1">API Operation</a></li>
            <li class="nav-item"><a class="nav-link scrollto" href="#item-2-2">Parameter Details</a></li>

            <li class="nav-item section-title mt-3"><a class="nav-link scrollto" href="#section-3"><span class="theme-icon-holder me-2"><i class="fas fa-cogs fa-fw"></i></span>Integration</a></li>
            <li class="nav-item"><a class="nav-link scrollto" href="#item-3-1">Sample Request</a></li>
            <li class="nav-item"><a class="nav-link scrollto" href="#item-3-2">Verify Request</a></li>

            <li class="nav-item section-title mt-3"><a class="nav-link scrollto" href="#section-3"><span class="theme-icon-holder me-2"><i class="fas fa-gift"></i></span>Modules</a></li>
            <li class="nav-item"><a class="nav-link scrollto" href="#item-3-4">Woocomerce Plugin</a></li>
            <li class="nav-item"><a class="nav-link scrollto" href="#item-3-5">WHMCS Module</a></li>
            <li class="nav-item"><a class="nav-link scrollto" href="#item-3-6">SmmPanel Module</a></li>
            <li class="nav-item"><a class="nav-link scrollto" href="#item-3-8">Sketchware SWB</a></li>
            <li class="nav-item"><a class="nav-link scrollto" href="#item-3-7">Mobile App </a></li>
         </ul>

      </nav><!--//docs-nav-->
   </div><!--//docs-sidebar-->
   <div class="docs-content">
      <div class="container">
         <article class="docs-article" id="section-1">
            <header class="docs-header">
               <h1 class="docs-heading">Welcome To <?= site_config("site_name") ?> Docs <span class="docs-time">Last updated: 2024-06-06</span></h1>
               <section class="docs-intro">
                  <p><?= site_config("site_name") ?> is a simple and Secure payment automation tool which is designed to use personal account as a payment gateway so that you can accept payments from your customer through your website where you will find a complete overview on how <?= site_config("site_name") ?> works and how you can integrate <?= site_config("site_name") ?> API in your website</p><br>
               </section><!--//docs-intro-->
         </article>

         <article class="docs-article" id="section-2">
            <header class="docs-header">
               <h1 class="docs-heading">API Introduction</h1>
               <section class="docs-intro">
                  <p><?= site_config("site_name") ?> Payment Gateway enables Merchants to receive money from their customers by temporarily redirecting them to www.<?= site_config("site_name") ?>.com. The gateway is connecting multiple payment terminal including card system, mobile financial system, local and International wallet. After the payment is complete, the customer is returned to the merchant's site and seconds later the Merchant receives notification about the payment along with the details of the transaction. This document is intended to be utilized by technical personnel supporting the online Merchant's website. Working knowledge of HTML forms or cURL is required. You will probably require test accounts for which you need to open accounts via contact with <?= site_config("site_name") ?>.com or already provided to you. </p>
               </section><!--//docs-intro-->
            </header>
            <section class="docs-section" id="item-2-1">
               <h2 class="section-heading">API Operation</h2>
               <p> REST APIs are supported in two environments. Use the Sandbox environment for testing purposes, then move to the live environment for production processing. When testing, generate an order url with your test credentials to make calls to the Sandbox URIs. When you’re set to go live, use the live credentials assigned to your new signature key to generate a live order url to be used with the live URIs. Your server has to support cURL system. For HTML Form submit please review after cURL part we provide HTML Post method URL also </p>

               <h2 xss=removed>Live API End Point (For Create Payment URL):</h2>
               <p xss=removed><?= PAYMENT_URL ?>api/payment/create</p>

               <h2 xss=removed>Payment Verify API:</h2>
               <p xss=removed><?= PAYMENT_URL ?>api/payment/verify</p>
            </section><!--//section-->

            <section class="docs-section" id="item-2-2">
               <h2 class="section-heading">Parameter Details</h2>
               <p class="text-info"> Variables Need to POST to Initialize Payment Process in gateway URL </p>

               <div class="table-responsive my-4">
                  <table class="table table-striped">
                     <thead>
                        <tr>
                           <th scope="col">Field Name</th>
                           <th scope="col">Description</th>
                           <th scope="col">Required</th>
                           <th scope="col">Example Values</th>
                        </tr>
                     </thead>
                     <tbody>


                        <tr>
                           <th scope="row">cus_name</th>
                           <td>Customer Full Name</td>
                           <td>Yes</td>
                           <td>John Doe</td>
                        </tr>
                        <tr>
                           <th scope="row">cus_email</th>
                           <td>Email address of the customer</td>
                           <td>Yes</td>
                           <td>john@gmail.com</td>
                        </tr>
                        <tr>
                           <th scope="row">amount</th>
                           <td>The total amount payable. Please note that you should skip the the trailing zeros in case the amount is a natural number.</td>
                           <td>Yes </td>
                           <td>10 or 10.50 or 10.6</td>
                        </tr>
                        <tr>
                           <th scope="row">success_url</th>
                           <td>URL to which the customer will be returned when the payment is made successfully. The customer will be returned to the last page on the Merchant's website where he should be notify the payment successful.</td>
                           <td>Yes</td>
                           <td>https://yourdomain.com/sucess.php</td>
                        </tr>
                        <tr>
                           <th scope="row">cancel_url</th>
                           <td>URL to return customer to your product page or home page.</td>
                           <td>Yes</td>
                           <td>https://yourdomain.com/cancel.php </td>
                        </tr>

                        <tr>
                           <th scope="row">meta_data</th>
                           <td>Pass any json formatted data </td>
                           <td>No</td>
                           <td>Json Formated </td>
                        </tr>

                     </tbody>
                  </table>
               </div><!--//table-responsive-->
               <p class="text-warning"> Variables Needs For Payment Verify </p>

               <div class="table-responsive my-4">
                  <table class="table table-striped">
                     <thead>
                        <tr>
                           <th scope="col">Field Name</th>
                           <th scope="col">Description</th>
                           <th scope="col">Required</th>
                           <th scope="col">Example Values</th>
                        </tr>
                     </thead>
                     <tbody>


                        <tr>
                           <th scope="row">transaction_id</th>
                           <td>Transaction id received as a query parameter from the success URL provided during payment creation.</td>
                           <td>Yes</td>
                           <td>OVKPXW165414</td>
                        </tr>


                     </tbody>
                  </table>
               </div><!--//table-responsive-->

               <h2 class="section-heading">Headers Details</h2>
               <div class="table-responsive my-4">
                  <table class="table table-striped">
                     <thead>
                        <tr>
                           <th scope="col">Header Name</th>
                           <th scope="col">Value</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <th scope="row">Content-Type</th>
                           <td>application/json</td>
                        </tr>
                        <tr>
                           <th scope="row">API-KEY</th>
                           <td>App key From API credentials</td>
                        </tr>
                        <tr>
                           <th scope="row">SECRET-KEY</th>
                           <td>Secret key From API credentials</td>
                        </tr>
                        <tr>
                           <th scope="row">BRAND-KEY</th>
                           <td>Brand key From Brands</td>
                        </tr>

                     </tbody>
                  </table>
               </div><!--//table-responsive-->

            </section><!--//section-->
         </article><!--//docs-article-->


         <article class="docs-article" id="section-3">
            <header class="docs-header">
               <h1 class="docs-heading">Integration</h1>
               <section class="docs-intro">
                  <p>You can integrate our payment gateway into your PHP Laravel WordPress WooCommerce sites.</p>
               </section><!--//docs-intro-->
            </header>
            <section class="docs-section" id="item-3-1">
               <h2 class="section-heading">Sample Request</h2>
               <?= view('Home\Views\developers\integration'); ?>

            </section><!--//section-->

            <section class="docs-section" id="item-3-2">
               <h2 class="section-heading">Verify Request</h2>

               <?= view('Home\Views\developers\integration2'); ?>

            </section><!--//section-->

            <section class="docs-section" id="item-3-4">


               <h2 class="section-heading">WordPress Module</h2>

               <p xss=removed>Integrate our payment gateway into your WordPress website effortlessly. Whether you run an e-commerce store, a membership site, or a donation platform, our WordPress module makes it easy to accept payments online. Download now and start accepting payments with ease!</p>

               <div class="row my-3">
                  <div class="col-md-6 col-12">
                     <ul class="list list-unstyled pl-0" xss=removed>
                        <li>
                           <a href="/public/assets/downloads/WP.zip" class="btn btn-primary">
                              <svg class="svg-inline--fa fa-download me-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="download" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                 <path fill="currentColor" d="M480 352h-133.5l-45.25 45.25C289.2 409.3 273.1 416 256 416s-33.16-6.656-45.25-18.75L165.5 352H32c-17.67 0-32 14.33-32 32v96c0 17.67 14.33 32 32 32h448c17.67 0 32-14.33 32-32v-96C512 366.3 497.7 352 480 352zM432 456c-13.2 0-24-10.8-24-24c0-13.2 10.8-24 24-24s24 10.8 24 24C456 445.2 445.2 456 432 456zM233.4 374.6C239.6 380.9 247.8 384 256 384s16.38-3.125 22.62-9.375l128-128c12.49-12.5 12.49-32.75 0-45.25c-12.5-12.5-32.76-12.5-45.25 0L288 274.8V32c0-17.67-14.33-32-32-32C238.3 0 224 14.33 224 32v242.8L150.6 201.4c-12.49-12.5-32.75-12.5-45.25 0c-12.49 12.5-12.49 32.75 0 45.25L233.4 374.6z"></path>
                              </svg> Download Now</a>
                        </li>
                     </ul>
                  </div>
               </div>
            </section><!--//section-->

            <section class="docs-section" id="item-3-5">
               <h2 class="section-heading">WHMCS Module</h2>

               <p xss=removed>Integrate our payment gateway seamlessly into your WHMCS setup. With our module, you can easily accept payments from your customers, manage invoices, and track transactions effortlessly. Get started with just a few clicks!</p>

               <div class="row my-3">
                  <div class="col-md-6 col-12">
                     <ul class="list list-unstyled pl-0" xss=removed>
                        <li>
                           <a href="/public/assets/downloads/WHMCS.zip" class="btn btn-primary">
                              <svg class="svg-inline--fa fa-download me-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="download" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                 <path fill="currentColor" d="M480 352h-133.5l-45.25 45.25C289.2 409.3 273.1 416 256 416s-33.16-6.656-45.25-18.75L165.5 352H32c-17.67 0-32 14.33-32 32v96c0 17.67 14.33 32 32 32h448c17.67 0 32-14.33 32-32v-96C512 366.3 497.7 352 480 352zM432 456c-13.2 0-24-10.8-24-24c0-13.2 10.8-24 24-24s24 10.8 24 24C456 445.2 445.2 456 432 456zM233.4 374.6C239.6 380.9 247.8 384 256 384s16.38-3.125 22.62-9.375l128-128c12.49-12.5 12.49-32.75 0-45.25c-12.5-12.5-32.76-12.5-45.25 0L288 274.8V32c0-17.67-14.33-32-32-32C238.3 0 224 14.33 224 32v242.8L150.6 201.4c-12.49-12.5-32.75-12.5-45.25 0c-12.49 12.5-12.49 32.75 0 45.25L233.4 374.6z"></path>
                              </svg> Download Now</a>
                        </li>
                     </ul>
                  </div>
               </div>
            </section><!--//section-->

            <section class="docs-section" id="item-3-6">


               <h2 class="section-heading">SMM Panel Module</h2>

               <p xss=removed>Enhance your SMM panel with our payment gateway integration module. Streamline the payment process for your social media marketing services and provide a seamless experience for your clients. Download the module now and take your SMM panel to the next level!</p>

               <div class="row my-3">
                  <div class="col-md-6 col-12">
                     <ul class="list list-unstyled pl-0" xss=removed>
                        <li>
                           <a href="/public/assets/downloads/SMM.zip" class="btn btn-primary">
                              <svg class="svg-inline--fa fa-download me-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="download" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                 <path fill="currentColor" d="M480 352h-133.5l-45.25 45.25C289.2 409.3 273.1 416 256 416s-33.16-6.656-45.25-18.75L165.5 352H32c-17.67 0-32 14.33-32 32v96c0 17.67 14.33 32 32 32h448c17.67 0 32-14.33 32-32v-96C512 366.3 497.7 352 480 352zM432 456c-13.2 0-24-10.8-24-24c0-13.2 10.8-24 24-24s24 10.8 24 24C456 445.2 445.2 456 432 456zM233.4 374.6C239.6 380.9 247.8 384 256 384s16.38-3.125 22.62-9.375l128-128c12.49-12.5 12.49-32.75 0-45.25c-12.5-12.5-32.76-12.5-45.25 0L288 274.8V32c0-17.67-14.33-32-32-32C238.3 0 224 14.33 224 32v242.8L150.6 201.4c-12.49-12.5-32.75-12.5-45.25 0c-12.49 12.5-12.49 32.75 0 45.25L233.4 374.6z"></path>
                              </svg> Download Now</a>
                        </li>
                     </ul>
                  </div>
               </div>
            </section><!--//section-->
            
           
            <section class="docs-section" id="item-3-7">


               <h1 class="section-heading">Mobile App</h1>
               
               <p xss=removed>See Setup Video: <a href="#">Video here/</a></p>

               <div class="row my-3">
                  <div class="col-md-6 col-12">
                     <ul class="list list-unstyled pl-0" xss=removed>
                        <li>
                           <a href="/public/assets/downloads/jonotapay.apk" class="btn btn-primary">
                              <svg class="svg-inline--fa fa-download me-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="download" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                 <path fill="currentColor" d="M480 352h-133.5l-45.25 45.25C289.2 409.3 273.1 416 256 416s-33.16-6.656-45.25-18.75L165.5 352H32c-17.67 0-32 14.33-32 32v96c0 17.67 14.33 32 32 32h448c17.67 0 32-14.33 32-32v-96C512 366.3 497.7 352 480 352zM432 456c-13.2 0-24-10.8-24-24c0-13.2 10.8-24 24-24s24 10.8 24 24C456 445.2 445.2 456 432 456zM233.4 374.6C239.6 380.9 247.8 384 256 384s16.38-3.125 22.62-9.375l128-128c12.49-12.5 12.49-32.75 0-45.25c-12.5-12.5-32.76-12.5-45.25 0L288 274.8V32c0-17.67-14.33-32-32-32C238.3 0 224 14.33 224 32v242.8L150.6 201.4c-12.49-12.5-32.75-12.5-45.25 0c-12.49 12.5-12.49 32.75 0 45.25L233.4 374.6z"></path>
                              </svg> Download Mobile App Now</a>
                        </li>
                     </ul>
                  </div>
               </div>
            </section>

         </article>
      </div>
   </div>
</div>