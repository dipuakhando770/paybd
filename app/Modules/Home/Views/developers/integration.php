<style type="text/css">
  .copy-button {
    display: none;
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 1;
  }

  .code-container:hover .copy-button {
    display: block;
  }
</style>

<div class="container mt-5">
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="example1-tab" data-bs-toggle="tab" data-bs-target="#example1" type="button" role="tab" aria-controls="example1" aria-selected="true">PHP</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="example2-tab" data-bs-toggle="tab" data-bs-target="#example2" type="button" role="tab" aria-controls="example2" aria-selected="false">PHP Guzzle</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="example3-tab" data-bs-toggle="tab" data-bs-target="#example3" type="button" role="tab" aria-controls="example3" aria-selected="false">Javascript Node</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="example4-tab" data-bs-toggle="tab" data-bs-target="#example4" type="button" role="tab" aria-controls="example4" aria-selected="false">Python</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="example5-tab" data-bs-toggle="tab" data-bs-target="#example5" type="button" role="tab" aria-controls="example5" aria-selected="false">Native</button>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active position-relative code-container" id="example1" role="tabpanel" aria-labelledby="example1-tab">
      <pre><code class="language-php">
      &lt;?php

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => '<?= PAYMENT_URL ?>api/payment/create',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"success_url":"yourdomain.com/success","cancel_url":"yourdomain.com/cancel","metadata":{"phone":"016****"},"amount":"10"}',
        CURLOPT_HTTPHEADER => array(
          'API-KEY: gnXi7etgWNhFyFGZFrOMYyrmnF4A1eGU5SC2QRmUvILOlNc2Ef',
          'Content-Type: application/json',
          'SECRET-KEY: YourSecretKeyHere',
          'BRAND-KEY: YourBrandKeyHere'
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);
      echo $response;

      ?&gt;
      </code></pre>
      <button class="btn btn-sm btn-secondary copy-button" onclick="copyCode(this)">&#x2398;</button>
    </div>
    <div class="tab-pane fade position-relative code-container" id="example2" role="tabpanel" aria-labelledby="example2-tab">
      <pre><code class="language-php">
      &lt;?php
      $client = new Client();
      $headers = [
        'API-KEY' => 'gnXi7etgWNhFyFGZFrOMYyrmnF4A1eGU5SC2QRmUvILOlNc2Ef',
        'Content-Type' => 'application/json',
        'SECRET-KEY' => 'YourSecretKeyHere',
        'BRAND-KEY' => 'YourBrandKeyHere'
      ];
      $body = '{
        "success_url": "yourdomain.com/success",
        "cancel_url": "yourdomain.com/cancel",
        "metadata": {
          "phone": "016****"
        },
        "amount": "10"
      }';
      $request = new Request('POST', '<?= PAYMENT_URL ?>api/payment/create', $headers, $body);
      $res = $client->sendAsync($request)->wait();
      echo $res->getBody();
      ?&gt;
      </code></pre>
      <button class="btn btn-sm btn-primary copy-button" onclick="copyCode(this)">&#x2398;</button>
    </div>
    <div class="tab-pane fade position-relative code-container" id="example3" role="tabpanel" aria-labelledby="example3-tab">
      <pre><code class="language-php">
      const axios = require('axios');
      let data = JSON.stringify({
        "success_url": "yourdomain.com/success",
        "cancel_url": "yourdomain.com/cancel",
        "metadata": {
          "phone": "016****"
        },
        "amount": "10"
      });

      let config = {
        method: 'post',
        maxBodyLength: Infinity,
        url: '<?= PAYMENT_URL ?>api/payment/create',
        headers: { 
          'API-KEY': 'gnXi7etgWNhFyFGZFrOMYyrmnF4A1eGU5SC2QRmUvILOlNc2Ef', 
          'Content-Type': 'application/json',
          'SECRET-KEY': 'YourSecretKeyHere',
          'BRAND-KEY': 'YourBrandKeyHere'
        },
        data : data
      };

      axios.request(config)
      .then((response) => {
        console.log(JSON.stringify(response.data));
      })
      .catch((error) => {
        console.log(error);
      });


      </code></pre>
      <button class="btn btn-sm btn-primary copy-button" onclick="copyCode(this)">&#x2398;</button>
    </div>

    <div class="tab-pane fade position-relative code-container" id="example4" role="tabpanel" aria-labelledby="example4-tab">
      <pre><code class="language-php">
      import requests
      import json

      url = "<?= PAYMENT_URL ?>api/payment/create"

      payload = json.dumps({
        "success_url": "yourdomain.com/success",
        "cancel_url": "yourdomain.com/cancel",
        "metadata": {
          "phone": "016****"
        },
        "amount": "10"
      })
      headers = {
        'API-KEY': 'gnXi7etgWNhFyFGZFrOMYyrmnF4A1eGU5SC2QRmUvILOlNc2Ef',
        'Content-Type': 'application/json',
        'SECRET-KEY': 'YourSecretKeyHere',
        'BRAND-KEY': 'YourBrandKeyHere'
      }

      response = requests.request("POST", url, headers=headers, data=payload)

      print(response.text)
      </code></pre>
      <button class="btn btn-sm btn-primary copy-button" onclick="copyCode(this)">&#x2398;</button>
    </div>

    <div class="tab-pane fade position-relative code-container" id="example5" role="tabpanel" aria-labelledby="example5-tab">
      <pre><code class="language-php">
      package main

      import (
        "fmt"
        "strings"
        "net/http"
        "io/ioutil"
      )

      func main() {

        url := "<?= PAYMENT_URL ?>api/payment/create"
        method := "POST"

        payload := strings.NewReader(`{"success_url":"yourdomain.com/success","cancel_url":"yourdomain.com/cancel","metadata":{"phone":"01521412457"},"amount":"10"}`)

        client := &http.Client {
        }
        req, err := http.NewRequest(method, url, payload)

        if err != nil {
          fmt.Println(err)
          return
        }
        req.Header.Add("API-KEY", "gnXi7etgWNhFyFGZFrOMYyrmnF4A1eGU5SC2QRmUvILOlNc2Ef")
        req.Header.Add("Content-Type", "application/json")
        req.Header.Add("SECRET-KEY", "YourSecretKeyHere")
        req.Header.Add("BRAND-KEY", "YourBrandKeyHere")

        res, err := client.Do(req)
        if err != nil {
          fmt.Println(err)
          return
        }
        defer res.Body.Close()

        body, err := ioutil.ReadAll(res.Body)
        if err != nil {
          fmt.Println(err)
          return
        }
        fmt.Println(string(body))
      }
      </code></pre>
      <button class="btn btn-sm btn-primary copy-button" onclick="copyCode(this)">&#x2398;</button>
    </div>

  </div>
</div>

<h3 class="section-heading">Response Details</h3>
<div class="table-responsive my-4">
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">Field Name</th>
        <th scope="col">Type</th>
        <th scope="col">Description</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th colspan="3" class="text-info">Success Response</th>
      </tr>
      <tr>
        <th scope="row">status</th>
        <td>bool</td>
        <td>TRUE</td>
      </tr>
      <tr>
        <th scope="row">message</th>
        <td>String</td>
        <td>Message for Status</td>
      </tr>
      <tr>
        <th scope="row">payment_url</th>
        <td>String</td>
        <td>Payment Link (where customers will complete their payment)</td>
      </tr>
      <tr>
        <th colspan="3" class="text-danger">Error Response</th>
      </tr>
      <tr>
        <th scope="row">status</th>
        <td>bool</td>
        <td>FALSE</td>
      </tr>
      <tr>
        <th scope="row">message</th>
        <td>String</td>
        <td>Message associated with the error response</td>
      </tr>
      <tr>
        <td colspan="3" style="max-width: 80%; word-wrap: break-word; overflow-wrap: break-word; word-break: break-all;color:green;font-weight:800">
          Completing Payment Page task you will be redirected to success or cancel page based on transaction status with the following Query Parameters:
          yourdomain.com/(success/cancel)?transactionId=******&paymentMethod=***&paymentAmount=**.**&paymentFee=**.**&status=pending or success or failed
        </td>
      </tr>
    </tbody>
  </table>
</div><!--//table-responsive-->
