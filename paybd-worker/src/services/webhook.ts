// Webhook Dispatcher Service with HMAC-SHA256 Signatures

export async function dispatchWebhook(
  webhookUrl: string,
  payload: Record<string, any>,
  secretKey: string
): Promise<{ success: boolean; status?: number; error?: string }> {
  try {
    const jsonBody = JSON.stringify(payload);
    const enc = new TextEncoder();
    
    // Create HMAC signature using the merchant's API Key / Brand Key
    const key = await crypto.subtle.importKey(
      'raw',
      enc.encode(secretKey),
      { name: 'HMAC', hash: 'SHA-256' },
      false,
      ['sign']
    );
    
    const signatureBuffer = await crypto.subtle.sign('HMAC', key, enc.encode(jsonBody));
    const signature = Array.from(new Uint8Array(signatureBuffer))
      .map(b => b.toString(16).padStart(2, '0'))
      .join('');
      
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 10000); // 10s timeout
    
    const res = await fetch(webhookUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'User-Agent': 'PayBD-Webhook-Worker/1.0',
        'X-PayBD-Signature': signature,
        'X-PayBD-Event': 'payment.status_changed'
      },
      body: jsonBody,
      signal: controller.signal
    });
    
    clearTimeout(timeout);
    return { success: res.ok, status: res.status };
  } catch (err: any) {
    return { success: false, error: err.message || 'Webhook dispatch failed' };
  }
}
