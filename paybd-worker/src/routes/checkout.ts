import { Hono } from 'hono';
import { Env, TempTransaction, Brand, UserPaymentSetting, ModuleData } from '../types';
import { renderCheckoutHtml } from '../views/checkoutHtml';
import { renderMethodHtml } from '../views/methodHtml';
import { renderReportHtml } from '../views/reportHtml';
import { extractAmountFromSms, matchesTransactionId } from '../services/smsMatcher';
import { dispatchWebhook } from '../services/webhook';

export const checkoutApi = new Hono<{ Bindings: Env }>();

const METHOD_META: Record<string, { name: string; logo: string; category: string }> = {
  bkash: {
    name: 'bKash',
    logo: 'https://paybd.nasirdigitalhub.com/assets/images/bkash.png',
    category: 'mobile'
  },
  nagad: {
    name: 'Nagad',
    logo: 'https://paybd.nasirdigitalhub.com/assets/images/nagad.png',
    category: 'mobile'
  },
  rocket: {
    name: 'Rocket',
    logo: 'https://paybd.nasirdigitalhub.com/assets/images/rocket.png',
    category: 'mobile'
  },
  upay: {
    name: 'Upay',
    logo: 'https://paybd.nasirdigitalhub.com/assets/images/upay.png',
    category: 'mobile'
  },
  cellfin: {
    name: 'Cellfin',
    logo: 'https://paybd.nasirdigitalhub.com/assets/images/cellfin.png',
    category: 'mobile'
  },
  bank: {
    name: 'Bank Transfer',
    logo: 'https://paybd.nasirdigitalhub.com/assets/images/bank.png',
    category: 'bank'
  },
  binance: {
    name: 'Binance Pay',
    logo: 'https://paybd.nasirdigitalhub.com/assets/images/binance.png',
    category: 'crypto'
  }
};

// GET /api/execute/:ids - Main Checkout UI
checkoutApi.get('/execute/:ids', async (c) => {
  const ids = c.req.param('ids');
  const db = c.env.DB;

  const trx = await db.prepare(
    'SELECT * FROM temp_transactions WHERE ids = ?'
  ).bind(ids).first<TempTransaction>();

  if (!trx || trx.status === 2) {
    return c.html('<h3>Invalid or Expired Payment Link</h3>', 404);
  }

  const brand = await db.prepare(
    'SELECT * FROM brands WHERE id = ?'
  ).bind(trx.brand_id).first<Brand>();

  if (!brand) {
    return c.html('<h3>Brand Not Found</h3>', 404);
  }

  // Calculate Fee
  let fee = 0;
  if (brand.fees > 0) {
    fee = brand.fees_type === 0 ? brand.fees : (trx.amount * brand.fees) / 100;
  }
  const totalAmount = Math.ceil(trx.amount + fee);

  // Fetch Merchant Payment Settings
  const { results: settings } = await db.prepare(
    'SELECT * FROM user_payment_settings WHERE uid = ? AND brand_id = ? AND status = 1'
  ).bind(trx.uid, trx.brand_id).all<UserPaymentSetting>();

  const activeMethods: Array<{ type: string; name: string; logo: string; category: string }> = [];

  for (const s of settings) {
    const meta = METHOD_META[s.g_type] || {
      name: s.g_type.toUpperCase(),
      logo: `https://placehold.co/80x40?text=${s.g_type}`,
      category: s.t_type
    };
    activeMethods.push({
      type: s.g_type,
      name: meta.name,
      logo: meta.logo,
      category: meta.category
    });
  }

  const params = JSON.parse(trx.params || '{}');

  const html = renderCheckoutHtml({
    brandName: brand.brand_name,
    brandLogo: brand.brand_logo,
    amount: trx.amount,
    fee,
    totalAmount,
    currency: brand.currency || 'BDT',
    transactionId: trx.transaction_id,
    ids: trx.ids,
    customerName: params.cus_name,
    customerEmail: params.cus_email,
    methods: activeMethods
  });

  return c.html(html);
});

// GET /api/execute_payment/:method/:ids - Method Execution Screen
checkoutApi.get('/execute_payment/:method/:ids', async (c) => {
  const method = c.req.param('method').toLowerCase();
  const ids = c.req.param('ids');
  const db = c.env.DB;

  const trx = await db.prepare(
    'SELECT * FROM temp_transactions WHERE ids = ?'
  ).bind(ids).first<TempTransaction>();

  if (!trx || trx.status === 2) {
    return c.html('<h3>Invalid or Expired Payment Link</h3>', 404);
  }

  const brand = await db.prepare(
    'SELECT * FROM brands WHERE id = ?'
  ).bind(trx.brand_id).first<Brand>();

  const setting = await db.prepare(
    'SELECT * FROM user_payment_settings WHERE uid = ? AND brand_id = ? AND g_type = ? AND status = 1'
  ).bind(trx.uid, trx.brand_id, method).first<UserPaymentSetting>();

  if (!setting || !brand) {
    return c.html('<h3>Payment Method Not Available</h3>', 404);
  }

  let fee = 0;
  if (brand.fees > 0) {
    fee = brand.fees_type === 0 ? brand.fees : (trx.amount * brand.fees) / 100;
  }
  const totalAmount = Math.ceil(trx.amount + fee);

  const settingParams = JSON.parse(setting.params || '{}');
  const meta = METHOD_META[method] || {
    name: method.toUpperCase(),
    logo: '',
    category: setting.t_type
  };

  const html = renderMethodHtml({
    method,
    methodName: meta.name,
    methodLogo: meta.logo,
    accountNumber: settingParams.number || settingParams.account_number || 'N/A',
    accountType: settingParams.account_type || 'Personal',
    instructions: settingParams.instructions || 'Send money to the number above and enter Transaction ID.',
    amount: totalAmount,
    currency: brand.currency || 'BDT',
    ids: trx.ids,
    transactionId: trx.transaction_id
  });

  return c.html(html);
});

// POST /api/save_payment/:method - Verify submitted TrxID
checkoutApi.post('/save_payment/:method', async (c) => {
  const method = c.req.param('method').toLowerCase();
  let body: any;
  try {
    body = await c.req.json();
  } catch {
    body = await c.req.parseBody();
  }

  const ids = body.tmp_id;
  const userTrxId = (body.transaction_id || '').trim();

  if (!ids || !userTrxId) {
    return c.json({ status: 'error', message: 'Transaction ID is required' }, 400);
  }

  const db = c.env.DB;
  const trx = await db.prepare(
    'SELECT * FROM temp_transactions WHERE ids = ?'
  ).bind(ids).first<TempTransaction>();

  if (!trx || trx.status === 2) {
    return c.json({ status: 'error', message: 'Invalid or already completed transaction' }, 400);
  }

  const brand = await db.prepare('SELECT * FROM brands WHERE id = ?').bind(trx.brand_id).first<Brand>();
  let fee = 0;
  if (brand && brand.fees > 0) {
    fee = brand.fees_type === 0 ? brand.fees : (trx.amount * brand.fees) / 100;
  }
  const expectedAmount = Math.ceil(trx.amount + fee);

  // Bank Transfer Workflow
  if (body.t_type === 'bank' || method === 'bank') {
    await db.prepare(`
      INSERT INTO bank_transaction_logs (ids, uid, brand_id, files, status, type, created_at)
      VALUES (?, ?, ?, ?, 1, ?, datetime('now'))
    `).bind(ids, trx.uid, trx.brand_id, userTrxId, method).run();

    return c.json({
      status: 'warning',
      message: 'Your request is sent for review!',
      redirect: `/api/checkout/${method}/${ids}/1`
    });
  }

  // Mobile Banking SMS verification
  const { results: candidates } = await db.prepare(
    'SELECT * FROM module_data WHERE uid = ? AND status = 0 ORDER BY id DESC LIMIT 50'
  ).bind(trx.uid).all<ModuleData>();

  let matchedRecord: ModuleData | null = null;

  for (const candidate of candidates) {
    if (matchesTransactionId(candidate.message, userTrxId)) {
      const parsedAmount = extractAmountFromSms(candidate.message, method);
      if (parsedAmount !== null && Math.abs(parsedAmount - expectedAmount) <= 1.0) {
        matchedRecord = candidate;
        break;
      }
    }
  }

  if (!matchedRecord) {
    return c.json({
      status: 'error',
      message: 'দুঃখিত, আপনার Transaction ID খুঁজে পাওয়া যায়নি অথবা টাকার পরিমাণ মেলেনি।'
    });
  }

  // Idempotently consume the SMS message
  await db.prepare(
    'UPDATE module_data SET status = 1, tmp_id = ? WHERE id = ?'
  ).bind(ids, matchedRecord.id).run();

  return c.json({
    status: 'success',
    title: 'Payment Successful',
    message: 'আপনার পেমেন্ট সঠিকভাবে সম্পন্ন হয়েছে।',
    redirect: `/api/checkout/${method}/${ids}/2`
  });
});

// GET /api/checkout/:method/:ids/:type - Finalize & Redirect
checkoutApi.get('/checkout/:method/:ids/:type', async (c) => {
  const method = c.req.param('method');
  const ids = c.req.param('ids');
  const typeStr = c.req.param('type');
  const type = parseInt(typeStr, 10); // 1: Pending, 2: Completed, 3: Failed

  const db = c.env.DB;
  const trx = await db.prepare(
    'SELECT * FROM temp_transactions WHERE ids = ?'
  ).bind(ids).first<TempTransaction>();

  if (!trx) {
    return c.html('<h3>Transaction not found</h3>', 404);
  }

  const brand = await db.prepare('SELECT * FROM brands WHERE id = ?').bind(trx.brand_id).first<Brand>();
  let fee = 0;
  if (brand && brand.fees > 0) {
    fee = brand.fees_type === 0 ? brand.fees : (trx.amount * brand.fees) / 100;
  }
  const totalAmount = Math.ceil(trx.amount + fee);

  const params = JSON.parse(trx.params || '{}');
  const returnUrl = type === 2 ? params.success_url : params.cancel_url;

  let st = 'failed';
  if (type === 1) st = 'pending';
  else if (type === 2) st = 'completed';

  // Settle transaction in DB safely
  if (type === 1 || type === 2) {
    // Check if already settled to maintain idempotency
    const existing = await db.prepare(
      'SELECT id FROM transactions WHERE ids = ?'
    ).bind(ids).first();

    if (!existing) {
      await db.prepare(`
        INSERT INTO transactions (
          ids, uid, brand_id, type, status, transaction_id, message, amount, currency, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, datetime('now'))
      `).bind(
        ids,
        trx.uid,
        trx.brand_id,
        method,
        type,
        trx.transaction_id,
        `Payment processed by ${method}. TrxID: ${trx.transaction_id}`,
        totalAmount,
        brand?.currency || 'BDT'
      ).run();

      await db.prepare(
        'UPDATE temp_transactions SET status = ?, updated_at = datetime("now") WHERE ids = ?'
      ).bind(type, ids).run();

      // Dispatch Webhook if configured
      if (params.webhook_url && brand) {
        c.executionCtx.waitUntil(
          dispatchWebhook(
            params.webhook_url,
            {
              paymentMethod: method,
              transactionId: trx.transaction_id,
              paymentAmount: trx.amount,
              paymentFee: fee,
              status: st,
              metadata: trx.meta ? JSON.parse(trx.meta) : null
            },
            brand.brand_key
          )
        );
      }
    }
  }

  const queryParams = new URLSearchParams({
    paymentMethod: method,
    transactionId: trx.transaction_id,
    paymentAmount: String(trx.amount),
    paymentFee: String(fee),
    status: st
  });

  const sep = returnUrl.includes('?') ? '&' : '?';
  const finalRedirectUrl = `${returnUrl}${sep}${queryParams.toString()}`;

  const html = renderReportHtml({
    status: st as any,
    title: st === 'completed' ? 'পেমেন্ট সফল হয়েছে!' : st === 'pending' ? 'পেমেন্ট পর্যালোচনায় আছে' : 'পেমেন্ট ব্যর্থ হয়েছে',
    message: st === 'completed' 
      ? `আপনার ৳ ${totalAmount} টাকার পেমেন্ট সফল হয়েছে।`
      : st === 'pending'
      ? 'আপনার পেমেন্ট তথ্য সফলভাবে জমা দেওয়া হয়েছে। খুব শীঘ্রই যাচাই করা হবে।'
      : 'পেমেন্ট সম্পন্ন করা সম্ভব হয়নি। অনুগ্রহ করে পুনরায় চেষ্টা করুন।',
    amount: totalAmount,
    transactionId: trx.transaction_id,
    paymentMethod: method,
    redirectUrl: finalRedirectUrl,
    brandName: brand?.brand_name,
    brandLogo: brand?.brand_logo
  });

  return c.html(html);
});
