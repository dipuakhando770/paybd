// Payment Result & Redirect Page

export function renderReportHtml(data: {
  status: 'completed' | 'pending' | 'failed';
  title: string;
  message: string;
  amount: number;
  transactionId: string;
  paymentMethod: string;
  redirectUrl: string;
  brandName?: string;
  brandLogo?: string;
}): string {
  const isSuccess = data.status === 'completed';
  const isPending = data.status === 'pending';
  
  const iconSvg = isSuccess ? `
    <div class="icon-wrap icon-success">
      <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
    </div>
  ` : isPending ? `
    <div class="icon-wrap icon-pending">
      <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
    </div>
  ` : `
    <div class="icon-wrap icon-failed">
      <svg viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
    </div>
  `;

  return `<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>${data.title} - PayBD</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
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
      padding: 32px 24px;
      text-align: center;
    }
    .icon-wrap {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 16px;
    }
    .icon-wrap svg { width: 32px; height: 32px; fill: #ffffff; }
    .icon-success { background: #16a34a; }
    .icon-pending { background: #f59e0b; }
    .icon-failed { background: #ef4444; }
    .title {
      font-size: 1.35rem;
      font-weight: 700;
      margin-bottom: 8px;
    }
    .message {
      font-size: 0.95rem;
      color: var(--text-muted);
      margin-bottom: 24px;
      line-height: 1.5;
    }
    .details-table {
      background: #f8fafc;
      border-radius: 12px;
      padding: 16px;
      margin-bottom: 24px;
      text-align: left;
      font-size: 0.9rem;
    }
    .details-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
    }
    .details-row:last-child { margin-bottom: 0; }
    .details-label { color: var(--text-muted); }
    .details-val { font-weight: 600; color: var(--text); }
    .countdown-text {
      font-size: 0.85rem;
      color: var(--text-muted);
      margin-bottom: 16px;
    }
    .redirect-btn {
      display: inline-block;
      width: 100%;
      padding: 12px;
      background: #0f172a;
      color: #ffffff;
      text-decoration: none;
      font-weight: 600;
      border-radius: 10px;
      transition: background 0.2s;
    }
    .redirect-btn:hover { background: #1e293b; }
  </style>
</head>
<body>
  <div class="container">
    ${iconSvg}
    <h1 class="title">${data.title}</h1>
    <p class="message">${data.message}</p>

    <div class="details-table">
      <div class="details-row">
        <span class="details-label">Transaction ID:</span>
        <span class="details-val">${data.transactionId}</span>
      </div>
      <div class="details-row">
        <span class="details-label">পেমেন্ট মেথড:</span>
        <span class="details-val">${data.paymentMethod.toUpperCase()}</span>
      </div>
      <div class="details-row">
        <span class="details-label">পরিশোধিত টাকা:</span>
        <span class="details-val">৳ ${data.amount.toFixed(2)}</span>
      </div>
    </div>

    <div class="countdown-text">
      আপনাকে <span id="sec">4</span> সেকেন্ডের মধ্যে মার্চেন্ট সাইটে ফিরিয়ে নিয়ে যাওয়া হচ্ছে...
    </div>

    <a href="${data.redirectUrl}" id="redirectLink" class="redirect-btn">এখনই ফিরে যান</a>
  </div>

  <script>
    let timeLeft = 4;
    const secEl = document.getElementById('sec');
    const redirectUrl = "${data.redirectUrl}";

    const timer = setInterval(() => {
      timeLeft--;
      if (secEl) secEl.innerText = timeLeft;
      if (timeLeft <= 0) {
        clearInterval(timer);
        window.location.href = redirectUrl;
      }
    }, 1000);
  </script>
</body>
</html>`;
}
