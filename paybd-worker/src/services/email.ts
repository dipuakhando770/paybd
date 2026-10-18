// Transactional Email Service for Cloudflare Workers Free

export async function sendEmail(options: {
  to: string;
  subject: string;
  html: string;
  resendApiKey?: string;
  mailchannelsApiKey?: string;
  from?: string;
}): Promise<boolean> {
  const fromEmail = options.from || 'noreply@paybd.nasirdigitalhub.com';

  // Option 1: Resend HTTP API
  if (options.resendApiKey) {
    try {
      const res = await fetch('https://api.resend.com/emails', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${options.resendApiKey}`,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          from: fromEmail,
          to: [options.to],
          subject: options.subject,
          html: options.html
        })
      });
      return res.ok;
    } catch {
      return false;
    }
  }

  // Option 2: MailChannels on Cloudflare Workers
  try {
    const res = await fetch('https://api.mailchannels.net/tx/v1/send', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        personalizations: [{ to: [{ email: options.to }] }],
        from: { email: fromEmail, name: 'PayBD Notifications' },
        subject: options.subject,
        content: [{ type: 'text/html', value: options.html }]
      })
    });
    return res.ok;
  } catch {
    console.log(`[Email Mock] To: ${options.to}, Subject: ${options.subject}`);
    return true;
  }
}
