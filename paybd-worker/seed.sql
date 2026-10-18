-- PayBD Seed Data for Cloudflare D1
-- Default Admin, Roles, Options & Demo Merchant

-- Roles
INSERT OR IGNORE INTO user_roles (id, name, permissions) VALUES 
(1, 'Super Admin', '["all"]'),
(2, 'Support Staff', '["view_tickets","view_transactions"]');

-- Plans
INSERT OR IGNORE INTO plans (id, name, price, device, website, days, status) VALUES 
(1, 'Starter Plan', 0.00, 1, 1, 365, 1),
(2, 'Business Plan', 1500.00, 5, 5, 30, 1),
(3, 'Unlimited Plan', 3500.00, -1, -1, 30, 1);

-- Default Options
INSERT OR IGNORE INTO options (name, value) VALUES 
('site_name', 'PayBD'),
('site_title', 'PayBD - Automated Payment Gateway'),
('currency_code', 'BDT'),
('currency_symbol', '৳'),
('enable_notification', '1'),
('is_clear_ticket', '1'),
('default_clear_ticket_days', '15'),
('email_from', 'noreply@paybd.nasirdigitalhub.com'),
('email_name', 'PayBD Support');

-- Default Admin Staff (email: admin@paybd.com, password: AdminPassword123!)
-- Hash generated using PBKDF2 standard
INSERT OR IGNORE INTO staffs (id, ids, email, password, first_name, last_name, role_id, status) VALUES 
(1, 'staff_admin_001', 'admin@paybd.com', 'pbkdf2:100000:c3ab8ff13720e8ad:5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'System', 'Admin', 1, 1);

-- Demo Merchant (email: merchant@paybd.com, password: MerchantPassword123!)
INSERT OR IGNORE INTO users (id, ids, email, password, first_name, last_name, phone, balance, status) VALUES 
(1, 'usr_merchant_001', 'merchant@paybd.com', 'pbkdf2:100000:c3ab8ff13720e8ad:5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'Nasir', 'Digital', '01700000000', 5000.00, 1);

-- Active User Plan for Merchant (1 Year validity)
INSERT OR IGNORE INTO user_plans (id, uid, plan_id, device, website, expire) VALUES 
(1, 1, 1, -1, -1, datetime('now', '+365 days'));

-- Demo Brand with known API Key for testing
INSERT OR IGNORE INTO brands (id, uid, brand_name, brand_key, brand_logo, domain, fees, fees_type, currency, status) VALUES 
(1, 1, 'Nasir Digital Hub', 'test_api_key_paybd_2026', 'https://nasirdigitalhub.com/logo.png', 'nasirdigitalhub.com', 0, 0, 'BDT', 1);

-- Demo Payment Settings for bKash, Nagad, Rocket
INSERT OR IGNORE INTO user_payment_settings (id, uid, brand_id, g_type, t_type, params, status) VALUES 
(1, 1, 1, 'bkash', 'mobile', '{"account_type":"personal","number":"01700000000","instructions":"Send Money to Personal bKash number and enter TrxID below","active_payments":{"personal":1}}', 1),
(2, 1, 1, 'nagad', 'mobile', '{"account_type":"personal","number":"01800000000","instructions":"Send Money to Personal Nagad number and enter TxnID below","active_payments":{"personal":1}}', 1),
(3, 1, 1, 'rocket', 'mobile', '{"account_type":"personal","number":"01900000000","instructions":"Send Money to Personal Rocket number and enter TxnID below","active_payments":{"personal":1}}', 1);

-- Demo Device for SMS Forwarder
INSERT OR IGNORE INTO devices (id, uid, user_email, device_name, device_key, device_ip) VALUES 
(1, 1, 'merchant@paybd.com', 'Primary Galaxy Phone', 'dev_key_sec_999888', '127.0.0.1');
