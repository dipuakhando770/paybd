import { Hono } from 'hono';
import { cors } from 'hono/cors';
import { Env } from './types';
import { paymentApi } from './routes/paymentApi';
import { checkoutApi } from './routes/checkout';
import { deviceApi } from './routes/deviceApi';
import { merchantApi } from './routes/merchantApi';
import { adminApi } from './routes/adminApi';

const app = new Hono<{ Bindings: Env }>();

// Security & CORS Middleware
app.use('/*', cors({
  origin: '*',
  allowHeaders: ['Content-Type', 'Authorization', 'API-KEY', 'X-Requested-With'],
  allowMethods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']
}));

// Health check endpoint
app.get('/health', (c) => {
  return c.json({
    status: 'healthy',
    gateway: 'PayBD Cloudflare Worker',
    version: '1.0.0',
    timestamp: new Date().toISOString()
  });
});

// Root welcome / Gateway info
app.get('/', (c) => {
  return c.html(`<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>PayBD - Automated Payment Gateway</title>
  <style>
    body { font-family: system-ui, -apple-system, sans-serif; background: #0f172a; color: #f8fafc; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
    .card { background: #1e293b; padding: 40px; border-radius: 16px; border: 1px solid #334155; text-align: center; max-width: 480px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5); }
    h1 { color: #38bdf8; margin-bottom: 12px; }
    p { color: #94a3b8; font-size: 0.95rem; line-height: 1.6; }
    .badge { display: inline-block; background: #0284c7; color: white; padding: 4px 12px; border-radius: 999px; font-size: 0.8rem; font-weight: 600; margin-top: 16px; }
  </style>
</head>
<body>
  <div class="card">
    <h1>PayBD Payment Gateway</h1>
    <p>Powered by Cloudflare Workers & Cloudflare D1. High-speed, serverless, automated payment processor for Bangladesh.</p>
    <div class="badge">Operational & Ready</div>
  </div>
</body>
</html>`);
});

// Mount Routes
app.route('/api', paymentApi);
app.route('/api', checkoutApi);
app.route('/api', deviceApi);
app.route('/api/merchant', merchantApi);
app.route('/api/admin', adminApi);

// 404 Handler
app.notFound((c) => {
  return c.json({ status: 0, message: 'Endpoint not found' }, 404);
});

// Error Handler
app.onError((err, c) => {
  console.error('Unhandled Gateway Error:', err);
  return c.json({ status: 0, message: 'Internal Server Error', error: err.message }, 500);
});

// Cloudflare Worker export with Scheduled Cron Triggers
export default {
  fetch: app.fetch,
  async scheduled(event: ScheduledEvent, env: Env, ctx: ExecutionContext) {
    ctx.waitUntil((async () => {
      console.log(`[Cron Trigger] Running cleanup at ${new Date().toISOString()}`);
      const db = env.DB;
      try {
        // Clear expired temp_transactions older than 3 days
        await db.prepare(`
          DELETE FROM temp_transactions 
          WHERE status = 0 AND datetime(created_at) < datetime('now', '-3 days')
        `).run();

        // Clear matched module_data older than 15 days
        await db.prepare(`
          DELETE FROM module_data 
          WHERE status = 1 AND datetime(created_at) < datetime('now', '-15 days')
        `).run();
      } catch (e) {
        console.error('[Cron Cleanup Error]', e);
      }
    })());
  }
};
