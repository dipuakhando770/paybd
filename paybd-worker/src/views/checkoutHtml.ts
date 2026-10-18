// Modern High-Fidelity Checkout Page (UddoktaPay / PayBD Style)

export function renderCheckoutHtml(data: {
  brandName: string;
  brandLogo?: string;
  amount: number;
  fee: number;
  totalAmount: number;
  currency: string;
  transactionId: string;
  ids: string;
  customerName?: string;
  customerEmail?: string;
  methods: Array<{ type: string; name: string; logo: string; category: string }>;
}): string {
  const methodCards = data.methods.map(m => `
    <a href="/api/execute_payment/${m.type}/${data.ids}" class="method-card">
      <div class="method-logo-wrap">
        <img src="${m.logo}" alt="${m.name}" onerror="this.src='https://placehold.co/80x40?text=${m.name}'" />
      </div>
      <span class="method-name">${m.name}</span>
      <span class="chevron">&rsaquo;</span>
    </a>
  `).join('');

  return `<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PayBD Checkout - ${data.brandName}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #0070f3;
      --primary-dark: #0051a8;
      --bg: #f8fafc;
      --card-bg: #ffffff;
      --text: #0f172a;
      --text-muted: #64748b;
      --border: #e2e8f0;
      --radius: 16px;
      --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
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
    .checkout-container {
      width: 100%;
      max-width: 440px;
      background: var(--card-bg);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      border: 1px solid var(--border);
      overflow: hidden;
      animation: fadeIn 0.3s ease-out;
    }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .header {
      padding: 24px 24px 20px;
      text-align: center;
      border-bottom: 1px dashed var(--border);
      background: linear-gradient(180deg, #f1f5f9 0%, #ffffff 100%);
    }
    .brand-logo {
      width: 56px;
      height: 56px;
      border-radius: 12px;
      object-fit: contain;
      margin: 0 auto 10px;
      display: block;
      background: #ffffff;
      padding: 4px;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    .brand-title {
      font-size: 1.15rem;
      font-weight: 700;
      color: var(--text);
    }
    .amount-box {
      margin-top: 14px;
    }
    .amount-label {
      font-size: 0.85rem;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .amount-val {
      font-size: 2.2rem;
      font-weight: 800;
      color: #0f172a;
      line-height: 1.2;
    }
    .fee-badge {
      font-size: 0.8rem;
      color: var(--text-muted);
      margin-top: 4px;
    }
    .content {
      padding: 20px 24px 28px;
    }
    .section-title {
      font-size: 0.9rem;
      font-weight: 600;
      color: var(--text-muted);
      margin-bottom: 14px;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .methods-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .method-card {
      display: flex;
      align-items: center;
      padding: 12px 16px;
      border: 1px solid var(--border);
      border-radius: 12px;
      text-decoration: none;
      color: var(--text);
      transition: all 0.2s ease;
      background: #ffffff;
    }
    .method-card:hover {
      border-color: var(--primary);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 112, 243, 0.08);
    }
    .method-logo-wrap {
      width: 48px;
      height: 34px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 14px;
    }
    .method-logo-wrap img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }
    .method-name {
      font-weight: 600;
      font-size: 0.98rem;
      flex: 1;
    }
    .chevron {
      font-size: 1.4rem;
      color: #94a3b8;
    }
    .footer-secure {
      text-align: center;
      margin-top: 24px;
      font-size: 0.78rem;
      color: var(--text-muted);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }
    .footer-secure svg { width: 14px; height: 14px; fill: #10b981; }
  </style>
</head>
<body>
  <div class="checkout-container">
    <div class="header">
      <img src="${data.brandLogo || 'https://paybd.nasirdigitalhub.com/favicon.ico'}" alt="${data.brandName}" class="brand-logo" />
      <h1 class="brand-title">${data.brandName}</h1>
      <div class="amount-box">
        <div class="amount-label">পরিশোধের পরিমাণ</div>
        <div class="amount-val">৳ ${data.totalAmount.toLocaleString('en-IN', { minimumFractionDigits: 2 })}</div>
        ${data.fee > 0 ? `<div class="fee-badge">মূল মূল্য: ৳${data.amount} + ফি: ৳${data.fee}</div>` : ''}
      </div>
    </div>
    <div class="content">
      <div class="section-title">
        <span>পেমেন্ট মাধ্যম বেছে নিন</span>
      </div>
      <div class="methods-list">
        ${methodCards}
      </div>
      <div class="footer-secure">
        <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
        <span>100% নিরাপদ ও এনক্রিপ্টেড পেমেন্ট গেটওয়ে</span>
      </div>
    </div>
  </div>
</body>
</html>`;
}
