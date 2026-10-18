// Method Payment Instruction & TrxID Submission Page

export function renderMethodHtml(data: {
  method: string;
  methodName: string;
  methodLogo: string;
  accountNumber: string;
  accountType: string;
  instructions: string;
  amount: number;
  currency: string;
  ids: string;
  transactionId: string;
}): string {
  return `<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>${data.methodName} পেমেন্ট - PayBD</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #e2136e; /* bKash magenta fallback or dynamic */
      --bg: #f8fafc;
      --card-bg: #ffffff;
      --text: #0f172a;
      --text-muted: #64748b;
      --border: #e2e8f0;
      --radius: 16px;
      --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Inter', 'Hind Siliguri', sans-serif;
      background: var(--bg);
      color: var(--text);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 16px;
    }
    .container {
      width: 100%;
      max-width: 440px;
      background: var(--card-bg);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      border: 1px solid var(--border);
      overflow: hidden;
    }
    .top-bar {
      display: flex;
      align-items: center;
      padding: 16px 20px;
      border-bottom: 1px solid var(--border);
      background: #fafafa;
    }
    .back-btn {
      text-decoration: none;
      color: var(--text-muted);
      font-weight: 600;
      font-size: 1.2rem;
      margin-right: 12px;
      display: flex;
      align-items: center;
    }
    .header-logo {
      height: 28px;
      margin-right: 10px;
    }
    .top-title {
      font-weight: 700;
      font-size: 1.05rem;
    }
    .content {
      padding: 24px;
    }
    .info-card {
      background: #f1f5f9;
      border-radius: 12px;
      padding: 16px;
      margin-bottom: 20px;
    }
    .info-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 12px;
    }
    .info-row:last-child { margin-bottom: 0; }
    .info-label {
      font-size: 0.88rem;
      color: var(--text-muted);
    }
    .info-val {
      font-weight: 700;
      font-size: 1rem;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .copy-btn {
      background: #ffffff;
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 3px 8px;
      font-size: 0.75rem;
      cursor: pointer;
      font-weight: 600;
      color: #0f172a;
      transition: all 0.2s;
    }
    .copy-btn:hover { background: #e2e8f0; }
    .instructions {
      font-size: 0.88rem;
      color: #334155;
      line-height: 1.6;
      margin-bottom: 24px;
      background: #fffbeb;
      border: 1px solid #fef3c7;
      padding: 14px;
      border-radius: 10px;
    }
    .instructions ol {
      margin-left: 20px;
    }
    .instructions li {
      margin-bottom: 6px;
    }
    .form-group {
      margin-bottom: 18px;
    }
    .form-label {
      display: block;
      font-size: 0.9rem;
      font-weight: 600;
      margin-bottom: 8px;
      color: var(--text);
    }
    .form-input {
      width: 100%;
      padding: 14px 16px;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      font-size: 1rem;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
      outline: none;
      transition: border 0.2s;
    }
    .form-input:focus {
      border-color: #0070f3;
      box-shadow: 0 0 0 3px rgba(0, 112, 243, 0.1);
    }
    .submit-btn {
      width: 100%;
      background: #0070f3;
      color: #ffffff;
      border: none;
      border-radius: 10px;
      padding: 14px;
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      transition: background 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }
    .submit-btn:hover {
      background: #0051a8;
    }
    .submit-btn:disabled {
      background: #94a3b8;
      cursor: not-allowed;
    }
    .alert-box {
      display: none;
      padding: 12px;
      border-radius: 8px;
      font-size: 0.88rem;
      margin-bottom: 16px;
      text-align: center;
    }
    .alert-error {
      background: #fef2f2;
      color: #ef4444;
      border: 1px solid #fee2e2;
    }
    .alert-success {
      background: #f0fdf4;
      color: #16a34a;
      border: 1px solid #dcfce7;
    }
    .spinner {
      display: none;
      width: 18px;
      height: 18px;
      border: 2px solid #ffffff;
      border-top-color: transparent;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
  </style>
</head>
<body>
  <div class="container">
    <div class="top-bar">
      <a href="/api/execute/${data.ids}" class="back-btn">&larr;</a>
      <img src="${data.methodLogo}" alt="${data.methodName}" class="header-logo" onerror="this.style.display='none'" />
      <span class="top-title">${data.methodName} পেমেন্ট</span>
    </div>

    <div class="content">
      <div id="alertBox" class="alert-box"></div>

      <div class="info-card">
        <div class="info-row">
          <span class="info-label">অ্যাকাউন্ট টাইপ</span>
          <span class="info-val">${data.accountType.toUpperCase()}</span>
        </div>
        <div class="info-row">
          <span class="info-label">${data.methodName} নম্বর</span>
          <span class="info-val">
            <span id="accNum">${data.accountNumber}</span>
            <button class="copy-btn" onclick="copyText('accNum', this)">কপি</button>
          </span>
        </div>
        <div class="info-row">
          <span class="info-label">পরিশোধের পরিমাণ</span>
          <span class="info-val">
            <span id="accAmount">৳ ${data.amount}</span>
            <button class="copy-btn" onclick="copyText('accAmount', this, true)">কপি</button>
          </span>
        </div>
      </div>

      <div class="instructions">
        <strong>পেমেন্ট করার নিয়মাবলী:</strong>
        <ol>
          <li>আপনার ${data.methodName} অ্যাপ অথবা ডায়াল অপশনে যান।</li>
          <li><strong>Send Money</strong> অথবা <strong>Payment</strong> অপশন সিলেক্ট করুন।</li>
          <li>উপরের নম্বরে ঠিক <strong>৳ ${data.amount}</strong> টাকা পাঠান।</li>
          <li>টাকা পাঠানোর পর প্রাপ্ত <strong>Transaction ID / TrxID</strong> নিচের ঘরে লিখুন এবং কনফার্ম বাটনে ক্লিক করুন।</li>
        </ol>
      </div>

      <form id="paymentForm" onsubmit="submitTrx(event)">
        <div class="form-group">
          <label class="form-label" for="trxInput">Transaction ID (TrxID / TxnID)</label>
          <input 
            type="text" 
            id="trxInput" 
            class="form-input" 
            placeholder="উদাহরণ: 9A2B3C4D5E" 
            required 
            minlength="6"
            autocomplete="off"
          />
        </div>

        <button type="submit" id="submitBtn" class="submit-btn">
          <span class="spinner" id="spinner"></span>
          <span id="btnText">পেমেন্ট নিশ্চিত করুন</span>
        </button>
      </form>
    </div>
  </div>

  <script>
    function copyText(elementId, btn, isNumeric = false) {
      let text = document.getElementById(elementId).innerText;
      if (isNumeric) text = text.replace(/[^0-9.]/g, '');
      navigator.clipboard.writeText(text).then(() => {
        const orig = btn.innerText;
        btn.innerText = 'কপি হয়েছে!';
        setTimeout(() => btn.innerText = orig, 1500);
      });
    }

    async function submitTrx(e) {
      e.preventDefault();
      const trxId = document.getElementById('trxInput').value.trim();
      const btn = document.getElementById('submitBtn');
      const spinner = document.getElementById('spinner');
      const btnText = document.getElementById('btnText');
      const alertBox = document.getElementById('alertBox');

      alertBox.style.display = 'none';
      btn.disabled = true;
      spinner.style.display = 'inline-block';
      btnText.innerText = 'যাচাই করা হচ্ছে...';

      try {
        const res = await fetch('/api/save_payment/${data.method}', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            tmp_id: '${data.ids}',
            transaction_id: trxId,
            t_type: 'mobile',
            method: '${data.method}'
          })
        });

        const data = await res.json();

        if (data.status === 'success' || data.status === 1) {
          alertBox.className = 'alert-box alert-success';
          alertBox.innerText = data.message || 'পেমেন্ট সফলভাবে যাচাই হয়েছে! রিডাইরেক্ট করা হচ্ছে...';
          alertBox.style.display = 'block';
          if (data.redirect) {
            setTimeout(() => window.location.href = data.redirect, 1200);
          }
        } else if (data.status === 'warning') {
          alertBox.className = 'alert-box alert-warning';
          alertBox.innerText = data.message;
          alertBox.style.display = 'block';
          if (data.redirect) {
            setTimeout(() => window.location.href = data.redirect, 1800);
          }
        } else {
          alertBox.className = 'alert-box alert-error';
          alertBox.innerText = data.message || 'Transaction ID টি পাওয়া যায়নি। অনুগ্রহ করে সঠিক আইডি লিখুন।';
          alertBox.style.display = 'block';
          btn.disabled = false;
          spinner.style.display = 'none';
          btnText.innerText = 'পেমেন্ট নিশ্চিত করুন';
        }
      } catch (err) {
        alertBox.className = 'alert-box alert-error';
        alertBox.innerText = 'সার্ভার যোগাযোগে সমস্যা হয়েছে। আবার চেষ্টা করুন।';
        alertBox.style.display = 'block';
        btn.disabled = false;
        spinner.style.display = 'none';
        btnText.innerText = 'পেমেন্ট নিশ্চিত করুন';
      }
    }
  </script>
</body>
</html>`;
}
