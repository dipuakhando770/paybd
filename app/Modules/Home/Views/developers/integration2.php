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
      <button class="nav-link active" id="mexample1-tab" data-bs-toggle="tab" data-bs-target="#mexample1" type="button" role="tab" aria-controls="example1" aria-selected="true">PHP</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="mexample2-tab" data-bs-toggle="tab" data-bs-target="#mexample2" type="button" role="tab" aria-controls="example2" aria-selected="false">PHP Guzzle</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="mexample3-tab" data-bs-toggle="tab" data-bs-target="#mexample3" type="button" role="tab" aria-controls="example3" aria-selected="false">Javascript Node</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="mexample4-tab" data-bs-toggle="tab" data-bs-target="#mexample4" type="button" role="tab" aria-controls="example4" aria-selected="false">Python</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="mexample5-tab" data-bs-toggle="tab" data-bs-target="#mexample5" type="button" role="tab" aria-controls="example5" aria-selected="false">Native</button>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active position-relative code-container" id="mexample1" role="tabpanel" aria-labelledby="example1-tab">
      <pre><code class="language-php">
&lt;?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => '<?= PAYMENT_URL ?>api/payment/verify',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{"transaction_id":"ABCDEFH"}',
  CURLOPT_HTTPHEADER => array(
    'API-KEY: gnXi7etgWNhFyFGZFrOMYyrmnF4A1eGU5SC2QRmUvILOlNc2Ef',
    'Content-Type: application/json',
    'SECRET-KEY: Secret key From API credentials',
    'BRAND-KEY: Brand key From Brands'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

?&gt;
      </code></pre>
      <button class="btn btn-sm btn-secondary copy-button" onclick="copyCode(this)">Copy Active Tab Content <span id="copy-icon">&#128203;</span></button>
    </div>
    <div class="tab-pane fade position-relative code-container" id="mexample2" role="tabpanel" aria-labelledby="example2-tab">
      <pre><code class="language-php">
&lt;?php
$client = new Client();
$headers = [
  'API-KEY' => 'gnXi7etgWNhFyFGZFrOMYyrmnF4A1eGU5SC2QRmUvILOlNc2Ef',
  'Content-Type' => 'application/json',
  'SECRET-KEY' => 'Secret key From API credentials',
  'BRAND-KEY' => 'Brand key From Brands'
];
$body = '{
  "transaction_id": "ABCDEFH"
}';
$request = new Request('POST', '<?= PAYMENT_URL ?>api/payment/verify', $headers, $body);
$res = $client->sendAsync($request)->wait();
echo $res->getBody();

?&gt;
      </code></pre>
      <button class="btn btn-sm btn-primary copy-button" onclick="copyCode(this)">Copy Active Tab Content <span id="copy-icon">&#128203;</span></button>
    </div>
    <div class="tab-pane fade position-relative code-container" id="mexample3" role="tabpanel" aria-labelledby="example3-tab">
      <pre><code class="language-php">
const axios = require('axios');
let data = JSON.stringify({
  "transaction_id": "ABCDEFH"
});

let config = {
  method: 'post',
  maxBodyLength: Infinity,
  url: '<?= PAYMENT_URL ?>api/payment/verify',
  headers: { 
    'API-KEY': 'gnXi7etgWNhFyFGZFrOMYyrmnF4A1eGU5SC2QRmUvILOlNc2Ef', 
    'Content-Type': 'application/json',
    'SECRET-KEY': 'Secret key From API credentials',
    'BRAND-KEY': 'Brand key From Brands'
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
      <button class="btn btn-sm btn-primary copy-button" onclick="copyCode(this)">Copy Active Tab Content <span id="copy-icon">&#128203;</span></button>
    </div>

    <div class="tab-pane fade position-relative code-container" id="mexample4" role="tabpanel" aria-labelledby="example4-tab">
      <pre><code class="language-php">
import http.client
import json

conn = http.client.HTTPSConnection("local.pay.expensivepay.com")
payload = json.dumps({
  "transaction_id": "ABCDEFH"
})
headers = {
  'API-KEY': 'gnXi7etgWNhFyFGZFrOMYyrmnF4A1eGU5SC2QRmUvILOlNc2Ef',
  'Content-Type': 'application/json',
  'SECRET-KEY': 'Secret key From API credentials',
  'BRAND-KEY': 'Brand key From Brands'
}
conn.request("POST", "/api/payment/verify", payload, headers)
res = conn.getresponse()
data = res.read()
print(data.decode("utf-8"))
      </code></pre>
      <button class="btn btn-sm btn-primary copy-button" onclick="copyCode(this)">Copy Active Tab Content <span id="copy-icon">&#128203;</span></button>
    </div>

    <div class="tab-pane fade position-relative code-container" id="mexample5" role="tabpanel" aria-labelledby="example5-tab">
      <pre><code class="language-php">
package main

import (
  "fmt"
  "strings"
  "net/http"
  "io/ioutil"
)

func main() {

  url := "<?= PAYMENT_URL ?>api/payment/verify"
  method := "POST"

  payload := strings.NewReader(`{"transaction_id":"ABCDEFH"}`)

  client := &http.Client {
  }
  req, err := http.NewRequest(method, url, payload)

  if err != nil {
    fmt.Println(err)
    return
  }
  req.Header.Add("API-KEY", "gnXi7etgWNhFyFGZFrOMYyrmnF4A1eGU5SC2QRmUvILOlNc2Ef")
  req.Header.Add("Content-Type", "application/json")
  req.Header.Add("SECRET-KEY", "Secret key From API credentials")
  req.Header.Add("BRAND-KEY", "Brand key From Brands")

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
      <button class="btn btn-sm btn-primary copy-button" onclick="copyCode(this)">Copy Active Tab Content <span id="copy-icon">&#128203;</span></button>
    </div>

  </div>
</div>

<span>Sample Response</span>
<div class="container">
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="m1example1-tab" data-bs-toggle="tab" data-bs-target="#m1example1" type="button" role="tab" aria-controls="example1" aria-selected="true">Json</button>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active position-relative code-container" id="m1example1" role="tabpanel" aria-labelledby="example1-tab">
      <pre><code class="language-php">
{
    "cus_name": "John Doe",
    "cus_email": "john@gmail.com",
    "amount": "900.000",
    "transaction_id": "OVKPXW165414",
    "metadata": {
      "phone": "015****",
    },
    "payment_method": "bkash",
    "status": "COMPLETED"
}
      </code></pre>
      <button class="btn btn-sm btn-primary copy-button" onclick="copyCode(this)">Copy Active Tab Content <span id="copy-icon">&#128203;</span></button>
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
        <td>string</td>
        <td>COMPLETED or PENDING or ERROR</td>
      </tr>
      <tr>
        <th scope="row">cus_name</th>
        <td>String</td>
        <td>Customer Name</td>
      </tr>
      <tr>
        <th scope="row">cus_email</th>
        <td>String</td>
        <td>Customer Email</td>
      </tr>
      <tr>
        <th scope="row">amount</th>
        <td>String</td>
        <td>Amount</td>
      </tr>
      <tr>
        <th scope="row">transaction_id</th>
        <td>String</td>
        <td>Transaction id Generated by System</td>
      </tr>
      <tr>
        <th scope="row">metadata</th>
        <td>json</td>
        <td>Metadata used for Payment creation</td>
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
    </tbody>
  </table>
</div><!--//table-responsive-->

<script>
  function copyCode(button) {
    const codeContainer = button.parentElement;
    const codeElement = codeContainer.querySelector("code");
    const codeText = codeElement.innerText;

    const textArea = document.createElement("textarea");
    textArea.value = codeText;
    document.body.appendChild(textArea);
    textArea.select();
    document.execCommand("copy");
    document.body.removeChild(textArea);

    button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16"><path d="M12.644 3.612a.5.5 0 0 1 .702.702l-6.25 6.25a.5.5 0 0 1-.702 0l-3.5-3.5a.5.5 0 0 1 .704-.708l2.794 2.793 5.546-5.546a.5.5 0 0 1 .612-.076z"/></svg>';
    setTimeout(() => {
      button.innerHTML = 'Copy Active Tab Content <span id="copy-icon">&#128203;</span>';
    }, 1500);
  }
</script>
