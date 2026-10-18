// Bangladeshi Mobile Banking SMS Parser & Transaction Matcher

export interface SmsMatchRule {
  addressKeywords: string[];
  trxPrefixes: string[];
  amountPatterns: RegExp[];
}

export const PROVIDER_RULES: Record<string, SmsMatchRule> = {
  bkash: {
    addressKeywords: ['bkash'],
    trxPrefixes: ['trxid', 'trx id', 'trxid:'],
    amountPatterns: [
      /(?:received|cash in|cash out)\s+(?:tk|bdt)?\s*([\d,.]+)/i,
      /(?:tk|bdt)\s*([\d,.]+)\s*(?:received|cash in)/i
    ]
  },
  nagad: {
    addressKeywords: ['nagad'],
    trxPrefixes: ['txnid:', 'txnid', 'trxid:', 'trxid'],
    amountPatterns: [
      /(?:amount|money received|amount:)\s*(?:tk|bdt)?\s*([\d,.]+)/i,
      /(?:tk|bdt)\s*([\d,.]+)/i
    ]
  },
  rocket: {
    addressKeywords: ['16216', 'rocket', 'dbbl'],
    trxPrefixes: ['txnid:', 'txnid', 'trxid'],
    amountPatterns: [
      /(?:credited by|amount:)\s*(?:tk\.?|bdt)?\s*([\d,.]+)/i,
      /(?:tk\.?|bdt)\s*([\d,.]+)\s*received/i
    ]
  },
  upay: {
    addressKeywords: ['upay'],
    trxPrefixes: ['trxid', 'trx id'],
    amountPatterns: [
      /(?:credited by|amount:)\s*(?:tk\.?|bdt)?\s*([\d,.]+)/i,
      /(?:tk\.?|bdt)\s*([\d,.]+)\s*has been received/i
    ]
  },
  cellfin: {
    addressKeywords: ['islami.bank', 'cellfin', 'ibbl'],
    trxPrefixes: ['trxid:', 'trxid', 'txnid'],
    amountPatterns: [
      /received\s+([\d,.]+)/i,
      /(?:tk|bdt)\s*([\d,.]+)/i
    ]
  },
  tap: {
    addressKeywords: ['tap'],
    trxPrefixes: ['trxid', 'trx id'],
    amountPatterns: [
      /(?:received|cash out of)\s+(?:tk|bdt)?\s*([\d,.]+)/i
    ]
  },
  surecash: {
    addressKeywords: ['16495', 'surecash'],
    trxPrefixes: ['txnid:', 'txnid'],
    amountPatterns: [
      /amount:\s*(?:tk)?\s*([\d,.]+)/i
    ]
  },
  ok_wallet: {
    addressKeywords: ['01401195496', 'okwallet', 'ok wallet'],
    trxPrefixes: ['trxid', 'txnid'],
    amountPatterns: [
      /received\s+(?:tk)?\s*([\d,.]+)/i
    ]
  },
  mcash: {
    addressKeywords: ['mcash'],
    trxPrefixes: ['trxid'],
    amountPatterns: [
      /received\s+(?:tk)?\s*([\d,.]+)/i
    ]
  }
};

export function extractAmountFromSms(smsText: string, method: string): number | null {
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

  // Generic fallback pattern for any Tk <digits>
  const genericMatch = smsText.match(/\b(?:tk|bdt)\.?\s*([\d,.]+)/i);
  if (genericMatch && genericMatch[1]) {
    const cleanNum = genericMatch[1].replace(/,/g, '').trim();
    const num = parseFloat(cleanNum);
    if (!isNaN(num)) return num;
  }

  return null;
}

export function matchesTransactionId(smsText: string, expectedTrxId: string): boolean {
  const cleanExpected = expectedTrxId.trim().toLowerCase();
  const cleanSms = smsText.toLowerCase();

  // Look for exact word boundary match of the transaction ID
  return cleanSms.includes(cleanExpected);
}
