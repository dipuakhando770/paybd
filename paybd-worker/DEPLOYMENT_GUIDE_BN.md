# PayBD Cloudflare Workers Free ডিপ্লয়মেন্ট ও মাইগ্রেশন গাইড (বাংলা)

এই নির্দেশিকাটি অনুসরণ করে আপনি আপনার PayBD পেমেন্ট গেটওয়েকে সম্পূর্ণ **Cloudflare Workers Free** এবং **Cloudflare D1**-এ ডিপ্লয় করতে পারবেন।

> **লক্ষ্য রাখুন:** আপনার মূল ডোমেইন `nasirdigitalhub.com` এবং এর বর্তমান Cloudflare Worker অক্ষত থাকবে। এটি সম্পূর্ণ আলাদা সাবডোমেইন `paybd.nasirdigitalhub.com`-এ চলবে।

---

## ১. প্রয়োজনীয় উপাদান (Prerequisites)

১. **Node.js**: সংস্করণ ১৮ বা তার বেশি (LTS ভার্সন প্রস্তাবিত)।
২. **Cloudflare Account**: একটি ফ্রি ক্লাউডফ্লেয়ার অ্যাকাউন্ট।
৩. **Wrangler CLI**: Cloudflare এর অফিসিয়াল ডিপ্লয়মেন্ট টুল (`npm install -g wrangler` বা প্রজেক্টের লোকাল `npx wrangler`)।

---

## ২. প্রজেক্ট ডিরেক্টরি পরিচিতি

আপনার তৈরি করা ক্লাউডফ্লেয়ার ওয়ার্কার প্রজেক্টটি `d:\_public_html\paybd\paybd-worker`-এ প্রস্তুত রয়েছে:
- `src/index.ts`: মূল এন্ট্রি পয়েন্ট, রাউটিং, ক্রন ক্লিনার (Scheduled Trigger)।
- `src/routes/paymentApi.ts`: `/api/payment/create` এবং `/api/payment/verify` মার্চেন্ট এপিআই।
- `src/routes/checkout.ts`: আধুনিক চেকআউট পেজ, পেমেন্ট মেথড নির্বাচন এবং ট্রানজ্যাকশন আইডি যাচাই।
- `src/routes/deviceApi.ts`: অ্যান্ড্রয়েড এসএমএস ফরোয়ার্ডার অ্যাপ সংযোগ (`/api/device-connect`, `/api/add-data`)।
- `src/routes/merchantApi.ts`: মার্চেন্ট রেজিস্ট্রেশন, লগইন, ব্র্যান্ড কি ও সেটিংস।
- `src/routes/adminApi.ts`: অ্যাডমিন ড্যাশবোর্ড, স্ট্যাটস, ইউজার ও ট্রানজ্যাকশন পর্যবেক্ষণ।
- `src/services/smsMatcher.ts`: বিকাশ, নগদ, রকেট, উপায়, সেলফিন ইত্যাদি এসএমএস অটোমেটিক ম্যাচিং লজিক।
- `src/services/auth.ts`: নেটিভ ওয়েব ক্রিপ্টো PBKDF2 পাসওয়ার্ড হ্যাশিং ও JWT ভেরিফিকেশন।
- `src/services/webhook.ts`: মার্চেন্টদের ইনস্ট্যান্ট ওয়েবহুক নোটিফিকেশন (HMAC-SHA256 সাইনড)।
- `schema.sql`: Cloudflare D1 (SQLite) ডাটাবেস স্কিমা (১৭টি টেবিল ও পারফরম্যান্স ইনডেক্স)।
- `seed.sql`: প্রাথমিক অ্যাডমিন, প্ল্যান ও ডেমো মার্চেন্ট ডেটা।

---

## ৩. ডিপ্লয় করার ধাপসমূহ (Step-by-Step Instructions)

### ধাপ ১: টার্মিনালে প্রবেশ করুন
পাওয়ারশেল (PowerShell) ওপেন করে প্রজেক্ট ফোল্ডারে প্রবেশ করুন:
```powershell
cd d:\_public_html\paybd\paybd-worker
```

### ধাপ ২: ক্লাউডফ্লেয়ার অ্যাকাউন্টে লগইন করুন
```powershell
npx wrangler login
```
ব্রাউজারে একটি লগইন পেজ খুলবে, সেখানে **Allow** ক্লিক করে অনুমোদন দিন।

### ধাপ ৩: Cloudflare D1 ডাটাবেস তৈরি করুন
ক্লাউডফ্লেয়ার ড্যাশবোর্ডে নতুন D1 ডাটাবেস তৈরি করতে চালান:
```powershell
npx wrangler d1 create paybd-d1
```
কমান্ডটি সম্পন্ন হলে টার্মিনালে একটি `database_id` দেখতে পাবেন। যেমন:
```text
database_name = "paybd-d1"
database_id   = "xxxx-xxxx-xxxx-xxxx"
```

### ধাপ ৪: `wrangler.jsonc` ফাইল আপডেট করুন
`d:\_public_html\paybd\paybd-worker\wrangler.jsonc` ফাইলটি ওপেন করুন এবং প্রাপ্ত `database_id` টি বসিয়ে দিন:
```jsonc
"d1_databases": [
  {
    "binding": "DB",
    "database_name": "paybd-d1",
    "database_id": "আপনার_প্রাপ্ত_DATABASE_ID"
  }
]
```

### ধাপ ৫: ডাটাবেস স্কিমা ও ইনিশিয়াল ডেটা ইমপোর্ট করুন
প্রথমে স্কিমা তৈরি করুন:
```powershell
npx wrangler d1 execute paybd-d1 --remote --file=./schema.sql
```
এরপর প্রাথমিক সিড ডেটা প্রবেশ করান:
```powershell
npx wrangler d1 execute paybd-d1 --remote --file=./seed.sql
```

### ধাপ ৬: সিক্রেট কি (Secrets) সেট করুন
নিরাপত্তার স্বার্থে JWT সিক্রেট ক্লাউডফ্লেয়ার সিক্রেটসে সংরক্ষণ করুন:
```powershell
npx wrangler secret put JWT_SECRET
```
ইনপুট চাইবে, সেখানে আপনার পছন্দমত একটি গোপন চাবি দিন (যেমন: `PayBD_Super_Secret_Key_2026_x!`).

### ধাপ ৭: সাবডোমেইন বাইন্ডিং (`paybd.nasirdigitalhub.com`)
`wrangler.jsonc` ফাইলে ইতিমধ্যেই কাস্টম ডোমেইন রুট কনফিগার করা আছে:
```jsonc
"routes": [
  {
    "pattern": "paybd.nasirdigitalhub.com/*",
    "custom_domain": true
  }
]
```
ওয়ার্কারটি ডিপ্লয় করলে ক্লাউডফ্লেয়ার স্বয়ংক্রিয়ভাবে আপনার `nasirdigitalhub.com` জোনের অধীনে `paybd` সাবডোমেইনের জন্য DNS এবং SSL তৈরি করে দেবে।

### ধাপ ৮: ডিপ্লয় করুন (Deploy)
```powershell
npx wrangler deploy
```

সফলভাবে সম্পন্ন হলে আপনার ওয়ার্কারটি `https://paybd.nasirdigitalhub.com`-এ লাইভ হয়ে যাবে!

---

## ৪. লাইভ পেমেন্ট গ্রহণের পূর্বে প্রয়োজনীয় টেস্ট ও কনফিগারেশন

১. **অ্যাডমিন পাসওয়ার্ড পরিবর্তন**:
   - প্রাথমিক অ্যাডমিন ইমেইল: `admin@paybd.com`
   - প্রাথমিক পাসওয়ার্ড: `AdminPassword123!`
   - অবিলম্বে লগইন করে পাসওয়ার্ড পরিবর্তন করুন।

২. **মার্চেন্ট এপিআই কি ও পার্সোনাল বিকাশ/নগদ নম্বর কনফিগারেশন**:
   - মার্চেন্ট অ্যাকাউন্টে লগইন করে বিকাশ, নগদ বা রকেটের নিজস্ব ব্যক্তিগত নম্বর ও নির্দেশিকা আপডেট করুন।

৩. **অ্যান্ড্রয়েড এসএমএস ফরোয়ার্ডার অ্যাপ (SMS Forwarder)**:
   - আপনার মার্চেন্ট সিম কার্ড যে অ্যান্ড্রয়েড ফোনে রয়েছে, সেখানে এসএমএস ফরোয়ার্ডার অ্যাপ ইন্সটল করুন।
   - অ্যাপে সার্ভার URL দিন: `https://paybd.nasirdigitalhub.com/api/add-data`
   - ইউজার ইমেইল ও ডিভাইস কি দিয়ে টেস্ট এসএমএস পুশ করে যাচাই করুন।

৪. **এন্ড-টু-এন্ড টেস্ট পেমেন্ট**:
   - আপনার ওয়েবসাইট থেকে একটি ১০ টাকার টেস্ট পেমেন্ট শুরু করুন।
   - পেমেন্ট পেজে বিকাশ/নগদ সিলেক্ট করে টাকা পাঠান এবং প্রাপ্ত ট্রানজ্যাকশন আইডি দিয়ে সাবমিট করুন।
   - স্বয়ংক্রিয়ভাবে পেমেন্ট কনফার্ম হয়ে আপনার ওয়েবসাইটের Success URL এ রিডাইরেক্ট হচ্ছে কিনা এবং Webhook ডেটা আসছে কিনা তা নিশ্চিত করুন।
