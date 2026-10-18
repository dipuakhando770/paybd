import { Hono } from 'hono';
import { Env, User, Brand, Device, UserPaymentSetting, Transaction } from '../types';
import { hashPassword, verifyPassword, createJwt, verifyJwt } from '../services/auth';

export const merchantApi = new Hono<{ Bindings: Env; Variables: { user: any } }>();

function generateHex(len = 16): string {
  const bytes = crypto.getRandomValues(new Uint8Array(len));
  return Array.from(bytes).map(b => b.toString(16).padStart(2, '0')).join('');
}

// Auth Middleware for Merchant routes
const merchantAuth = async (c: any, next: () => Promise<void>) => {
  const authHeader = c.req.header('Authorization');
  if (!authHeader || !authHeader.startsWith('Bearer ')) {
    return c.json({ status: 0, message: 'Unauthorized. Bearer token required.' }, 401);
  }
  const token = authHeader.substring(7);
  const secret = c.env.JWT_SECRET || 'paybd_super_secret_jwt_key_2026';
  const payload = await verifyJwt(token, secret);
  if (!payload || payload.role !== 'merchant') {
    return c.json({ status: 0, message: 'Invalid or expired session' }, 401);
  }
  c.set('user', payload);
  await next();
};

// POST /api/merchant/register
merchantApi.post('/register', async (c) => {
  const body = await c.req.json();
  const { email, password, first_name, last_name, phone } = body;

  if (!email || !password || !first_name || !last_name) {
    return c.json({ status: 0, message: 'All fields are required' }, 400);
  }

  const db = c.env.DB;
  const existing = await db.prepare('SELECT id FROM users WHERE email = ?').bind(email).first();
  if (existing) {
    return c.json({ status: 0, message: 'Email already registered' }, 400);
  }

  const hashedPassword = await hashPassword(password);
  const ids = 'usr_' + generateHex(12);

  const res = await db.prepare(`
    INSERT INTO users (ids, email, password, first_name, last_name, phone, balance, status, created_at)
    VALUES (?, ?, ?, ?, ?, ?, 0.00, 1, datetime('now'))
  `).bind(ids, email, hashedPassword, first_name, last_name, phone || '').run();

  const userId = res.meta.last_row_id;

  // Auto assign default Starter plan for 1 year
  await db.prepare(`
    INSERT INTO user_plans (uid, plan_id, device, website, expire, created_at)
    VALUES (?, 1, -1, -1, datetime('now', '+365 days'), datetime('now'))
  `).bind(userId).run();

  // Create default Brand
  const brandKey = 'key_' + generateHex(16);
  await db.prepare(`
    INSERT INTO brands (uid, brand_name, brand_key, currency, status, created_at)
    VALUES (?, ?, ?, 'BDT', 1, datetime('now'))
  `).bind(userId, `${first_name}'s Store`, brandKey).run();

  const secret = c.env.JWT_SECRET || 'paybd_super_secret_jwt_key_2026';
  const token = await createJwt({ uid: userId, email, role: 'merchant' }, secret);

  return c.json({
    status: 1,
    message: 'Registration successful',
    token,
    user: { id: userId, email, first_name, last_name }
  });
});

// POST /api/merchant/login
merchantApi.post('/login', async (c) => {
  const body = await c.req.json();
  const { email, password } = body;

  if (!email || !password) {
    return c.json({ status: 0, message: 'Email and password required' }, 400);
  }

  const db = c.env.DB;
  const user = await db.prepare('SELECT * FROM users WHERE email = ? AND deleted_at IS NULL').bind(email).first<User>();

  if (!user) {
    return c.json({ status: 0, message: 'Invalid credentials' }, 401);
  }

  const match = await verifyPassword(password, user.password);
  if (!match) {
    return c.json({ status: 0, message: 'Invalid credentials' }, 401);
  }

  const secret = c.env.JWT_SECRET || 'paybd_super_secret_jwt_key_2026';
  const token = await createJwt({ uid: user.id, email: user.email, role: 'merchant' }, secret);

  return c.json({
    status: 1,
    message: 'Login successful',
    token,
    user: {
      id: user.id,
      email: user.email,
      first_name: user.first_name,
      last_name: user.last_name,
      balance: user.balance
    }
  });
});

// Protected routes below
merchantApi.use('/*', merchantAuth);

// GET /api/merchant/me
merchantApi.get('/me', async (c) => {
  const jwtUser = c.get('user');
  const db = c.env.DB;
  const user = await db.prepare(
    'SELECT id, ids, email, first_name, last_name, phone, balance, status, created_at FROM users WHERE id = ?'
  ).bind(jwtUser.uid).first<User>();

  const brand = await db.prepare('SELECT * FROM brands WHERE uid = ? AND deleted_at IS NULL LIMIT 1').bind(jwtUser.uid).first<Brand>();
  const plan = await db.prepare('SELECT * FROM user_plans WHERE uid = ? ORDER BY id DESC LIMIT 1').bind(jwtUser.uid).first();

  return c.json({ status: 1, user, brand, plan });
});

// GET /api/merchant/brands
merchantApi.get('/brands', async (c) => {
  const jwtUser = c.get('user');
  const db = c.env.DB;
  const { results } = await db.prepare('SELECT * FROM brands WHERE uid = ? AND deleted_at IS NULL').bind(jwtUser.uid).all<Brand>();
  return c.json({ status: 1, brands: results });
});

// POST /api/merchant/brands
merchantApi.post('/brands', async (c) => {
  const jwtUser = c.get('user');
  const body = await c.req.json();
  const db = c.env.DB;

  const brandKey = 'key_' + generateHex(16);
  await db.prepare(`
    INSERT INTO brands (uid, brand_name, brand_key, brand_logo, domain, fees, fees_type, currency, status, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, 'BDT', 1, datetime('now'))
  `).bind(
    jwtUser.uid,
    body.brand_name || 'My Store',
    brandKey,
    body.brand_logo || '',
    body.domain || '',
    parseFloat(body.fees) || 0,
    parseInt(body.fees_type, 10) || 0
  ).run();

  return c.json({ status: 1, message: 'Brand created successfully', brand_key: brandKey });
});

// GET /api/merchant/payment-settings
merchantApi.get('/payment-settings', async (c) => {
  const jwtUser = c.get('user');
  const db = c.env.DB;
  const { results } = await db.prepare('SELECT * FROM user_payment_settings WHERE uid = ?').bind(jwtUser.uid).all<UserPaymentSetting>();
  return c.json({ status: 1, settings: results });
});

// POST /api/merchant/payment-settings
merchantApi.post('/payment-settings', async (c) => {
  const jwtUser = c.get('user');
  const body = await c.req.json();
  const db = c.env.DB;

  const { brand_id, g_type, t_type, params, status } = body;

  const existing = await db.prepare(
    'SELECT id FROM user_payment_settings WHERE uid = ? AND brand_id = ? AND g_type = ?'
  ).bind(jwtUser.uid, brand_id, g_type).first();

  if (existing) {
    await db.prepare(`
      UPDATE user_payment_settings 
      SET params = ?, status = ?, updated_at = datetime('now')
      WHERE id = ?
    `).bind(typeof params === 'object' ? JSON.stringify(params) : params, status ?? 1, existing.id).run();
  } else {
    await db.prepare(`
      INSERT INTO user_payment_settings (uid, brand_id, g_type, t_type, params, status, created_at)
      VALUES (?, ?, ?, ?, ?, ?, datetime('now'))
    `).bind(jwtUser.uid, brand_id, g_type, t_type || 'mobile', typeof params === 'object' ? JSON.stringify(params) : params, status ?? 1).run();
  }

  return c.json({ status: 1, message: `${g_type} settings updated successfully` });
});

// GET /api/merchant/transactions
merchantApi.get('/transactions', async (c) => {
  const jwtUser = c.get('user');
  const db = c.env.DB;
  const { results } = await db.prepare(
    'SELECT * FROM transactions WHERE uid = ? ORDER BY id DESC LIMIT 50'
  ).bind(jwtUser.uid).all<Transaction>();
  return c.json({ status: 1, transactions: results });
});

// GET /api/merchant/devices
merchantApi.get('/devices', async (c) => {
  const jwtUser = c.get('user');
  const db = c.env.DB;
  const { results } = await db.prepare('SELECT * FROM devices WHERE uid = ? AND deleted_at IS NULL').bind(jwtUser.uid).all<Device>();
  return c.json({ status: 1, devices: results });
});

// POST /api/merchant/devices
merchantApi.post('/devices', async (c) => {
  const jwtUser = c.get('user');
  const body = await c.req.json();
  const db = c.env.DB;

  const deviceKey = 'dev_' + generateHex(12);
  await db.prepare(`
    INSERT INTO devices (uid, user_email, device_name, device_key, created_at)
    VALUES (?, ?, ?, ?, datetime('now'))
  `).bind(jwtUser.uid, jwtUser.email, body.device_name || 'My Phone', deviceKey).run();

  return c.json({ status: 1, message: 'Device registered successfully', device_key: deviceKey });
});
