import { Hono } from 'hono';
import { Env, Device } from '../types';

export const deviceApi = new Hono<{ Bindings: Env }>();

// Helper to extract param from JSON or form or query
async function getParam(c: any, key: string): Promise<string | undefined> {
  const queryVal = c.req.query(key);
  if (queryVal) return queryVal;
  try {
    const json = await c.req.json();
    if (json[key]) return String(json[key]);
  } catch {}
  try {
    const body = await c.req.parseBody();
    if (body[key]) return String(body[key]);
  } catch {}
  return undefined;
}

// Device connect route: GET / POST /api/device-connect
deviceApi.all('/device-connect', async (c) => {
  const userEmail = await getParam(c, 'user_email');
  const deviceKey = await getParam(c, 'device_key');
  const deviceIp = (await getParam(c, 'device_ip')) || c.req.header('CF-Connecting-IP') || '127.0.0.1';

  if (!userEmail || !deviceKey) {
    return c.json({ status: '0', message: 'user_email and device_key are required' });
  }

  const db = c.env.DB;
  const device = await db.prepare(
    'SELECT * FROM devices WHERE user_email = ? AND device_key = ? AND deleted_at IS NULL'
  ).bind(userEmail, deviceKey).first<Device>();

  if (!device) {
    return c.json({ status: '0', message: 'Failed to connect with the server. Device not found.' });
  }

  // Validate active subscription plan
  const plan = await db.prepare(
    'SELECT * FROM user_plans WHERE uid = ? AND datetime(expire) > datetime("now") ORDER BY id DESC LIMIT 1'
  ).bind(device.uid).first();

  if (!plan) {
    return c.json({ status: '2', message: 'Your key is expired' });
  }

  // Bind or verify device IP
  if (!device.device_ip) {
    await db.prepare('UPDATE devices SET device_ip = ? WHERE id = ?').bind(deviceIp, device.id).run();
    return c.json({ status: '1', message: String(device.uid) });
  } else if (device.device_ip === deviceIp) {
    return c.json({ status: '1', message: String(device.uid) });
  } else {
    // Update IP to allow dynamic mobile IP changes securely
    await db.prepare('UPDATE devices SET device_ip = ? WHERE id = ?').bind(deviceIp, device.id).run();
    return c.json({ status: '1', message: String(device.uid) });
  }
});

// Add SMS data route: GET / POST /api/add-data
deviceApi.all('/add-data', async (c) => {
  const userEmail = await getParam(c, 'user_email');
  const deviceKey = await getParam(c, 'device_key');
  const deviceIp = (await getParam(c, 'device_ip')) || c.req.header('CF-Connecting-IP') || '127.0.0.1';

  const address = await getParam(c, 'address');
  const message = await getParam(c, 'message');

  if (!userEmail || !deviceKey || !address || !message) {
    return c.json({ status: 0, message: 'Missing required parameters (user_email, device_key, address, message)' });
  }

  const db = c.env.DB;
  const device = await db.prepare(
    'SELECT * FROM devices WHERE user_email = ? AND device_key = ? AND deleted_at IS NULL'
  ).bind(userEmail, deviceKey).first<Device>();

  if (!device) {
    return c.json({ status: 0, message: 'Failed to connect or invalid device' });
  }

  const cleanMessage = message.replace(/\r?\n/g, ' ').trim();

  await db.prepare(`
    INSERT INTO module_data (uid, message, address, status, created_at)
    VALUES (?, ?, ?, 0, datetime('now'))
  `).bind(device.uid, cleanMessage, address.toLowerCase()).run();

  return c.json({ status: 1, message: 'Data inserted successfully' });
});
