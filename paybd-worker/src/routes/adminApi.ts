import { Hono } from 'hono';
import { Env, Staff, User, Transaction } from '../types';
import { verifyPassword, createJwt, verifyJwt } from '../services/auth';

export const adminApi = new Hono<{ Bindings: Env; Variables: { admin: any } }>();

const adminAuth = async (c: any, next: () => Promise<void>) => {
  const authHeader = c.req.header('Authorization');
  if (!authHeader || !authHeader.startsWith('Bearer ')) {
    return c.json({ status: 0, message: 'Unauthorized' }, 401);
  }
  const token = authHeader.substring(7);
  const secret = c.env.JWT_SECRET || 'paybd_super_secret_jwt_key_2026';
  const payload = await verifyJwt(token, secret);
  if (!payload || payload.role !== 'admin') {
    return c.json({ status: 0, message: 'Admin access required' }, 403);
  }
  c.set('admin', payload);
  await next();
};

// POST /api/admin/login
adminApi.post('/login', async (c) => {
  const body = await c.req.json();
  const { email, password } = body;

  const db = c.env.DB;
  const staff = await db.prepare(
    'SELECT * FROM staffs WHERE email = ? AND status = 1 AND deleted_at IS NULL'
  ).bind(email).first<Staff>();

  if (!staff) {
    return c.json({ status: 0, message: 'Invalid admin credentials' }, 401);
  }

  const match = await verifyPassword(password, staff.password);
  if (!match) {
    return c.json({ status: 0, message: 'Invalid admin credentials' }, 401);
  }

  const secret = c.env.JWT_SECRET || 'paybd_super_secret_jwt_key_2026';
  const token = await createJwt({ uid: staff.id, email: staff.email, role: 'admin' }, secret);

  return c.json({
    status: 1,
    message: 'Admin login successful',
    token,
    admin: {
      id: staff.id,
      email: staff.email,
      first_name: staff.first_name,
      last_name: staff.last_name
    }
  });
});

adminApi.use('/*', adminAuth);

// GET /api/admin/stats
adminApi.get('/stats', async (c) => {
  const db = c.env.DB;

  const totalUsers = await db.prepare('SELECT COUNT(id) as count FROM users WHERE deleted_at IS NULL').first<{ count: number }>();
  const totalTrx = await db.prepare('SELECT COUNT(id) as count, COALESCE(SUM(amount), 0) as volume FROM transactions WHERE status = 2').first<{ count: number; volume: number }>();
  const pendingTrx = await db.prepare('SELECT COUNT(id) as count FROM temp_transactions WHERE status = 0').first<{ count: number }>();

  return c.json({
    status: 1,
    stats: {
      total_merchants: totalUsers?.count || 0,
      completed_transactions: totalTrx?.count || 0,
      total_volume: totalTrx?.volume || 0,
      pending_transactions: pendingTrx?.count || 0
    }
  });
});

// GET /api/admin/users
adminApi.get('/users', async (c) => {
  const db = c.env.DB;
  const { results } = await db.prepare(
    'SELECT id, ids, email, first_name, last_name, phone, balance, status, created_at FROM users ORDER BY id DESC LIMIT 100'
  ).all<User>();
  return c.json({ status: 1, users: results });
});

// GET /api/admin/transactions
adminApi.get('/transactions', async (c) => {
  const db = c.env.DB;
  const { results } = await db.prepare(
    'SELECT * FROM transactions ORDER BY id DESC LIMIT 100'
  ).all<Transaction>();
  return c.json({ status: 1, transactions: results });
});
