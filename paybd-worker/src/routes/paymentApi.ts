import { Hono } from 'hono';
import { Env, Brand, TempTransaction, Transaction } from '../types';

export const paymentApi = new Hono<{ Bindings: Env }>();

function generateHex(len = 16): string {
  const bytes = crypto.getRandomValues(new Uint8Array(len));
  return Array.from(bytes).map(b => b.toString(16).padStart(2, '0')).join('');
}

function generateTrxId(): string {
  const dateStr = new Date().toISOString().slice(2, 10).replace(/-/g, '');
  const rand = generateHex(4).toUpperCase();
  return `TRX${dateStr}${rand}`;
}

// POST /api/payment/create
paymentApi.post('/payment/create', async (c) => {
  const apiKey = c.req.header('API-KEY') || c.req.query('api_key') || (await c.req.parseBody())['api_key'] as string;
  
  if (!apiKey) {
    return c.json({ status: 0, message: 'API-KEY is required' }, 401);
  }

  const db = c.env.DB;
  
  // Validate Brand Key
  const brand = await db.prepare(
    'SELECT * FROM brands WHERE brand_key = ? AND status = 1 AND deleted_at IS NULL'
  ).bind(apiKey).first<Brand>();

  if (!brand) {
    return c.json({ status: 0, message: 'Invalid API Request. Brand not found or inactive.' }, 404);
  }

  // Validate Merchant Plan
  const plan = await db.prepare(
    'SELECT * FROM user_plans WHERE uid = ? AND datetime(expire) > datetime("now") ORDER BY id DESC LIMIT 1'
  ).bind(brand.uid).first();

  if (!plan) {
    return c.json({ status: 0, message: 'Merchant subscription plan has expired.' }, 403);
  }

  let body: any;
  try {
    body = await c.req.json();
  } catch {
    body = await c.req.parseBody();
  }

  const amount = parseFloat(body.amount);
  if (!amount || isNaN(amount) || amount <= 0 || amount > 1000000) {
    return c.json({ status: 0, message: 'Invalid or missing amount. Maximum limit 1,000,000 BDT.' }, 400);
  }

  if (!body.success_url || !body.cancel_url) {
    return c.json({ status: 0, message: 'success_url and cancel_url are required.' }, 400);
  }

  const ids = generateHex(16);
  const transactionId = generateTrxId();
  
  const params = {
    cus_name: body.cus_name || 'Customer',
    cus_email: body.cus_email || 'customer@example.com',
    cus_phone: body.cus_phone || '',
    success_url: body.success_url,
    cancel_url: body.cancel_url,
    webhook_url: body.webhook_url || ''
  };

  const meta = typeof body.metadata === 'object' ? JSON.stringify(body.metadata) : (body.metadata || '{}');

  await db.prepare(`
    INSERT INTO temp_transactions (
      ids, uid, brand_id, params, meta, request, amount, transaction_id, status, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, datetime('now'))
  `).bind(
    ids,
    brand.uid,
    brand.id,
    JSON.stringify(params),
    meta,
    body.return_type || 'GET',
    amount,
    transactionId
  ).run();

  const appUrl = c.env.APP_URL || 'https://paybd.nasirdigitalhub.com';
  const paymentUrl = `${appUrl}/api/execute/${ids}`;

  return c.json({
    status: 1,
    message: 'Payment Link Created',
    payment_url: paymentUrl,
    transaction_id: transactionId
  });
});

// POST /api/payment/verify
paymentApi.post('/payment/verify', async (c) => {
  const apiKey = c.req.header('API-KEY') || c.req.query('api_key') || (await c.req.parseBody())['api_key'] as string;
  
  if (!apiKey) {
    return c.json({ status: 0, message: 'API-KEY is required' }, 401);
  }

  const db = c.env.DB;
  const brand = await db.prepare(
    'SELECT * FROM brands WHERE brand_key = ? AND status = 1'
  ).bind(apiKey).first<Brand>();

  if (!brand) {
    return c.json({ status: 0, message: 'Invalid API Request' }, 404);
  }

  let body: any;
  try {
    body = await c.req.json();
  } catch {
    body = await c.req.parseBody();
  }

  const transactionId = body.transaction_id || c.req.query('transaction_id');
  if (!transactionId) {
    return c.json({ status: 0, message: 'transaction_id is required' }, 400);
  }

  const tempTrx = await db.prepare(
    'SELECT * FROM temp_transactions WHERE transaction_id = ? AND uid = ?'
  ).bind(transactionId, brand.uid).first<TempTransaction>();

  if (!tempTrx) {
    return c.json({ status: 0, message: 'Transaction not found' }, 404);
  }

  const settledTrx = await db.prepare(
    'SELECT * FROM transactions WHERE ids = ? AND uid = ?'
  ).bind(tempTrx.ids, brand.uid).first<Transaction>();

  let statusText = 'PENDING';
  if (tempTrx.status === 2) statusText = 'COMPLETED';
  else if (tempTrx.status === 3) statusText = 'ERROR';

  const params = JSON.parse(tempTrx.params || '{}');

  return c.json({
    status: statusText,
    amount: tempTrx.amount,
    transaction_id: tempTrx.transaction_id,
    payment_method: settledTrx ? settledTrx.type : null,
    cus_name: params.cus_name || '',
    cus_email: params.cus_email || '',
    metadata: tempTrx.meta ? JSON.parse(tempTrx.meta) : null
  });
});
