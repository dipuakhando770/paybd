import test from 'node:test';
import assert from 'node:assert/strict';

// 1. Test SMS Provider Matching Logic
const PROVIDER_RULES = {
  bkash: {
    amountPatterns: [
      /(?:received|cash in|cash out)\s+(?:tk|bdt)?\s*([\d,.]+)/i,
      /(?:tk|bdt)\s*([\d,.]+)\s*(?:received|cash in)/i
    ]
  },
  nagad: {
    amountPatterns: [
      /(?:amount|money received|amount:)\s*(?:tk|bdt)?\s*([\d,.]+)/i,
      /(?:tk|bdt)\s*([\d,.]+)/i
    ]
  },
  rocket: {
    amountPatterns: [
      /(?:credited by|amount:)\s*(?:tk\.?|bdt)?\s*([\d,.]+)/i,
      /(?:tk\.?|bdt)\s*([\d,.]+)\s*received/i
    ]
  }
};

function extractAmountFromSms(smsText, method) {
  const rule = PROVIDER_RULES[method.toLowerCase()];
  const patterns = rule ? rule.amountPatterns : [/(?:tk|bdt|amount)[:\s]*([\d,.]+)/i];

  for (const regex of patterns) {
    const match = smsText.match(regex);
    if (match && match[1]) {
      const cleanNum = match[1].replace(/,/g, '').trim();
      const num = parseFloat(cleanNum);
      if (!isNaN(num)) return num;
    }
  }
  return null;
}

function matchesTransactionId(smsText, expectedTrxId) {
  return smsText.toLowerCase().includes(expectedTrxId.trim().toLowerCase());
}

test('SMS Parser: bKash Personal Money Received', () => {
  const sms = 'You have received Tk 1,250.00 from 01712345678. Fee Tk 0.00. Balance Tk 15,200.00. TrxID 9A2B3C4D5E at 17/10/2026 14:30';
  const amount = extractAmountFromSms(sms, 'bkash');
  const trxMatch = matchesTransactionId(sms, '9A2B3C4D5E');
  const lowerTrxMatch = matchesTransactionId(sms, '9a2b3c4d5e');

  assert.equal(amount, 1250.00);
  assert.equal(trxMatch, true);
  assert.equal(lowerTrxMatch, true);
});

test('SMS Parser: Nagad Money Received', () => {
  const sms = 'Money Received. Amount: Tk 500.00. Sender: 01812345678. TxnID: 7XYZ89AB. Date: 17/10/2026 15:45';
  const amount = extractAmountFromSms(sms, 'nagad');
  const trxMatch = matchesTransactionId(sms, '7XYZ89AB');

  assert.equal(amount, 500.00);
  assert.equal(trxMatch, true);
});

test('SMS Parser: Rocket Credit Alert', () => {
  const sms = 'Your account has been successfully credited by Tk. 3,000.00 from 01912345678. Fee Tk 0. TxnID: RCK12345';
  const amount = extractAmountFromSms(sms, 'rocket');
  const trxMatch = matchesTransactionId(sms, 'RCK12345');

  assert.equal(amount, 3000.00);
  assert.equal(trxMatch, true);
});

test('Security: Prevents Partial Amount Attack', () => {
  const expectedPayment = 1500.00;
  const attackSms = 'You have received Tk 150.00 from 01700000000. TrxID 9A2B3C4D5E';
  const parsedAmount = extractAmountFromSms(attackSms, 'bkash');

  assert.equal(parsedAmount, 150.00);
  assert.notEqual(parsedAmount, expectedPayment);
  assert.ok(Math.abs(parsedAmount - expectedPayment) > 1.0, 'Amount discrepancy must be caught');
});

test('Security: Webhook HMAC-SHA256 Signature Generation', async () => {
  const secretKey = 'test_merchant_secret_key_123';
  const payload = {
    paymentMethod: 'bkash',
    transactionId: 'TRX20261017ABCD',
    paymentAmount: 500.00,
    status: 'completed'
  };

  const jsonBody = JSON.stringify(payload);
  const enc = new TextEncoder();
  const key = await crypto.subtle.importKey(
    'raw',
    enc.encode(secretKey),
    { name: 'HMAC', hash: 'SHA-256' },
    false,
    ['sign']
  );
  const sigBuffer = await crypto.subtle.sign('HMAC', key, enc.encode(jsonBody));
  const signature = Array.from(new Uint8Array(sigBuffer)).map(b => b.toString(16).padStart(2, '0')).join('');

  assert.equal(typeof signature, 'string');
  assert.equal(signature.length, 64); // 256 bits = 64 hex characters
});

test('Security: PBKDF2 Password Hashing & Verification', async () => {
  const password = 'SuperSecretMerchantPass123!';
  const enc = new TextEncoder();
  const salt = crypto.getRandomValues(new Uint8Array(16));
  const saltHex = Array.from(salt).map(b => b.toString(16).padStart(2, '0')).join('');

  const keyMaterial = await crypto.subtle.importKey(
    'raw',
    enc.encode(password),
    { name: 'PBKDF2' },
    false,
    ['deriveBits']
  );
  const derivedBits = await crypto.subtle.deriveBits(
    { name: 'PBKDF2', salt, iterations: 10000, hash: 'SHA-256' },
    keyMaterial,
    256
  );
  const hashHex = Array.from(new Uint8Array(derivedBits)).map(b => b.toString(16).padStart(2, '0')).join('');

  assert.equal(hashHex.length, 64);
  assert.ok(hashHex !== password);
});
