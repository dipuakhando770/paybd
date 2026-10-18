export interface Env {
  DB: D1Database;
  APP_ENV?: string;
  APP_NAME?: string;
  APP_URL?: string;
  CURRENCY_CODE?: string;
  JWT_SECRET?: string;
  RESEND_API_KEY?: string;
  MAILCHANNELS_API_KEY?: string;
}

export interface User {
  id: number;
  ids: string;
  email: string;
  password: string;
  first_name: string;
  last_name: string;
  phone?: string;
  balance: number;
  api_credentials?: string;
  timezone?: string;
  ref_id?: number;
  ref_key?: string;
  addons?: string;
  more_information?: string;
  avatar?: string;
  activation_key?: string;
  reset_key?: string;
  status: number;
  created_at: string;
  updated_at: string;
  deleted_at?: string | null;
}

export interface Staff {
  id: number;
  ids: string;
  email: string;
  password: string;
  first_name: string;
  last_name: string;
  role_id: number;
  more_information?: string;
  avatar?: string;
  status: number;
  created_at: string;
  updated_at: string;
  deleted_at?: string | null;
}

export interface Brand {
  id: number;
  uid: number;
  brand_name: string;
  brand_key: string;
  brand_logo?: string;
  domain?: string;
  ip?: string;
  fees: number;
  fees_type: number; // 0: fixed, 1: percentage
  currency: string;
  meta?: string;
  status: number;
  created_at: string;
  deleted_at?: string | null;
}

export interface Device {
  id: number;
  uid: number;
  user_email: string;
  device_name: string;
  device_key: string;
  device_ip?: string;
  created_at: string;
  deleted_at?: string | null;
}

export interface UserPaymentSetting {
  id: number;
  uid: number;
  brand_id: number;
  g_type: string;
  t_type: string;
  params: string; // JSON
  status: number;
  created_at: string;
  updated_at: string;
}

export interface TempTransaction {
  id: number;
  ids: string;
  uid: number;
  brand_id: number;
  params: string; // JSON: cus_name, cus_email, cus_phone, success_url, cancel_url, webhook_url
  meta?: string;
  request: string;
  amount: number;
  transaction_id: string;
  status: number; // 0: pending, 1: pending review, 2: completed, 3: cancelled/failed, 9: binance pending
  created_at: string;
  updated_at: string;
}

export interface Transaction {
  id: number;
  ids: string;
  uid: number;
  brand_id: number;
  type: string;
  status: number;
  transaction_id: string;
  message?: string;
  amount: number;
  currency: string;
  created_at: string;
}

export interface ModuleData {
  id: number;
  uid: number;
  tmp_id?: string;
  message: string;
  address: string;
  status: number;
  created_at: string;
}
