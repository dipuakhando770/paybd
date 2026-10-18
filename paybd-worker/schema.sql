-- PayBD Cloudflare D1 (SQLite) Complete Schema
-- Generated for Cloudflare Workers Free Migration

-- 1. Users (Merchants)
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    ids TEXT NOT NULL UNIQUE,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    phone TEXT,
    balance REAL DEFAULT 0.00,
    api_credentials TEXT,
    timezone TEXT DEFAULT 'Asia/Dhaka',
    ref_id INTEGER DEFAULT 0,
    ref_key TEXT,
    addons TEXT,
    more_information TEXT,
    avatar TEXT,
    activation_key TEXT,
    reset_key TEXT,
    status INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME
);

-- 2. Staffs & Admins
CREATE TABLE IF NOT EXISTS staffs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    ids TEXT NOT NULL UNIQUE,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    role_id INTEGER DEFAULT 1,
    more_information TEXT,
    avatar TEXT,
    activation_key TEXT,
    reset_key TEXT,
    status INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME
);

-- 3. User Roles
CREATE TABLE IF NOT EXISTS user_roles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    permissions TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 4. Subscription Plans
CREATE TABLE IF NOT EXISTS plans (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    price REAL DEFAULT 0.00,
    device INTEGER DEFAULT 1,
    website INTEGER DEFAULT 1,
    days INTEGER DEFAULT 30,
    status INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 5. User Plans
CREATE TABLE IF NOT EXISTS user_plans (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    uid INTEGER NOT NULL,
    plan_id INTEGER NOT NULL,
    device INTEGER DEFAULT 1,
    website INTEGER DEFAULT 1,
    expire DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uid) REFERENCES users(id) ON DELETE CASCADE
);

-- 6. Merchant Brands (API Key scoping)
CREATE TABLE IF NOT EXISTS brands (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    uid INTEGER NOT NULL,
    brand_name TEXT NOT NULL,
    brand_key TEXT NOT NULL UNIQUE,
    brand_logo TEXT,
    domain TEXT,
    ip TEXT,
    fees REAL DEFAULT 0.00,
    fees_type INTEGER DEFAULT 0, -- 0: fixed, 1: percentage
    currency TEXT DEFAULT 'BDT',
    meta TEXT, -- JSON support_mail, mobile_number, whatsapp_number
    status INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME,
    FOREIGN KEY (uid) REFERENCES users(id) ON DELETE CASCADE
);

-- 7. Devices (SMS Forwarders)
CREATE TABLE IF NOT EXISTS devices (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    uid INTEGER NOT NULL,
    user_email TEXT NOT NULL,
    device_name TEXT DEFAULT 'Android Forwarder',
    device_key TEXT NOT NULL UNIQUE,
    device_ip TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME,
    FOREIGN KEY (uid) REFERENCES users(id) ON DELETE CASCADE
);

-- 8. User Payment Settings (Payment Methods config per merchant & brand)
CREATE TABLE IF NOT EXISTS user_payment_settings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    uid INTEGER NOT NULL,
    brand_id INTEGER NOT NULL,
    g_type TEXT NOT NULL, -- bkash, nagad, rocket, upay, cellfin, binance, etc.
    t_type TEXT NOT NULL, -- mobile, bank, int_b
    params TEXT NOT NULL, -- JSON config (numbers, mode, keys, active_payments)
    status INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uid) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE CASCADE
);

-- 9. Temporary Transactions (Checkout Sessions)
CREATE TABLE IF NOT EXISTS temp_transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    ids TEXT NOT NULL UNIQUE,
    uid INTEGER NOT NULL,
    brand_id INTEGER NOT NULL,
    params TEXT NOT NULL, -- JSON (cus_name, cus_email, cus_phone, success_url, cancel_url, webhook_url)
    meta TEXT,
    request TEXT DEFAULT 'GET',
    amount REAL NOT NULL,
    transaction_id TEXT NOT NULL UNIQUE,
    status INTEGER DEFAULT 0, -- 0: Pending, 1: Review/Pending, 2: Completed, 3: Failed/Canceled, 9: Binance Pending
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uid) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE CASCADE
);

-- 10. Completed / Settled Transactions
CREATE TABLE IF NOT EXISTS transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    ids TEXT NOT NULL,
    uid INTEGER NOT NULL,
    brand_id INTEGER NOT NULL,
    type TEXT NOT NULL, -- bkash, nagad, rocket, upay, bank, binance, etc.
    status INTEGER NOT NULL, -- 1: Pending Review, 2: Completed, 3: Failed
    transaction_id TEXT NOT NULL,
    message TEXT,
    amount REAL NOT NULL,
    currency TEXT DEFAULT 'BDT',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uid) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE CASCADE
);

-- 11. Module Data (SMS Sync from Merchant Android Devices)
CREATE TABLE IF NOT EXISTS module_data (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    uid INTEGER NOT NULL,
    tmp_id TEXT,
    message TEXT NOT NULL,
    address TEXT NOT NULL, -- bkash, nagad, 16216, upay, etc.
    status INTEGER DEFAULT 0, -- 0: Unused/Available, 1: Consumed/Matched
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uid) REFERENCES users(id) ON DELETE CASCADE
);

-- 12. Manual Bank Transaction Receipts
CREATE TABLE IF NOT EXISTS bank_transaction_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    ids TEXT NOT NULL,
    uid INTEGER NOT NULL,
    brand_id INTEGER NOT NULL,
    files TEXT NOT NULL, -- TrxID / Deposit Receipt Ref
    status INTEGER DEFAULT 1,
    type TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uid) REFERENCES users(id) ON DELETE CASCADE
);

-- 13. Invoices (Manual Merchant Invoices)
CREATE TABLE IF NOT EXISTS invoice (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    ids TEXT NOT NULL UNIQUE,
    uid INTEGER NOT NULL,
    brand_id INTEGER NOT NULL,
    customer_name TEXT NOT NULL,
    customer_number TEXT,
    customer_amount REAL NOT NULL,
    customer_email TEXT,
    customer_address TEXT,
    customer_description TEXT,
    status INTEGER DEFAULT 0,
    pay_status INTEGER DEFAULT 0,
    transaction_id TEXT,
    extras TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME,
    FOREIGN KEY (uid) REFERENCES users(id) ON DELETE CASCADE
);

-- 14. Notifications
CREATE TABLE IF NOT EXISTS notifications (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    uid INTEGER NOT NULL,
    message TEXT NOT NULL,
    admin_status INTEGER DEFAULT 1,
    is_read INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uid) REFERENCES users(id) ON DELETE CASCADE
);

-- 15. System Options
CREATE TABLE IF NOT EXISTS options (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,
    value TEXT
);

-- 16. Asynchronous Queue
CREATE TABLE IF NOT EXISTS queue (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    task_type TEXT NOT NULL,
    task_data TEXT NOT NULL,
    status TEXT DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 17. Activity Logs
CREATE TABLE IF NOT EXISTS activity_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    uid INTEGER NOT NULL,
    action TEXT NOT NULL,
    ip TEXT,
    user_agent TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Performance Indexes
CREATE INDEX IF NOT EXISTS idx_temp_trx_ids ON temp_transactions(ids);
CREATE INDEX IF NOT EXISTS idx_temp_trx_trxid ON temp_transactions(transaction_id);
CREATE INDEX IF NOT EXISTS idx_temp_trx_uid ON temp_transactions(uid);
CREATE INDEX IF NOT EXISTS idx_trx_trxid ON transactions(transaction_id);
CREATE INDEX IF NOT EXISTS idx_trx_uid ON transactions(uid);
CREATE INDEX IF NOT EXISTS idx_brands_key ON brands(brand_key);
CREATE INDEX IF NOT EXISTS idx_devices_key ON devices(device_key);
CREATE INDEX IF NOT EXISTS idx_module_data_uid_status ON module_data(uid, status, address);
CREATE INDEX IF NOT EXISTS idx_user_payment_settings_lookup ON user_payment_settings(uid, brand_id, g_type);
