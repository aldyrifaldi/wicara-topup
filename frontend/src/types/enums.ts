// Order Status Enum
export enum OrderStatus {
  PENDING = 'pending',
  PROCESSING = 'processing',
  COMPLETED = 'completed',
  FAILED = 'failed',
  CANCELLED = 'cancelled',
  REFUNDED = 'refunded'
}

// Payment Method Enum
export enum PaymentMethod {
  BANK_TRANSFER = 'bank_transfer',
  EWALLET = 'ewallet',
  CREDIT_CARD = 'credit_card',
  DEBIT_CARD = 'debit_card',
  QR_CODE = 'qr_code',
  COUNTER = 'counter'
}

// User Role Enum
export enum UserRole {
  CUSTOMER = 'customer',
  ADMIN = 'admin',
  SUPER_ADMIN = 'super_admin'
}

// Transaction Type Enum
export enum TransactionType {
  TOPUP = 'topup',
  PURCHASE = 'purchase',
  REFUND = 'refund',
  REWARD = 'reward',
  BONUS = 'bonus',
  WITHDRAWAL = 'withdrawal'
}

// Notification Type Enum
export enum NotificationType {
  ORDER = 'order',
  PROMOTION = 'promotion',
  SYSTEM = 'system',
  SECURITY = 'security',
  REWARD = 'reward'
}

// Product Status Enum
export enum ProductStatus {
  ACTIVE = 'active',
  INACTIVE = 'inactive',
  OUT_OF_STOCK = 'out_of_stock'
}

// Discount Type Enum
export enum DiscountType {
  PERCENTAGE = 'percentage',
  FIXED = 'fixed'
}

// Level Enum
export enum Level {
  BRONZE = 'bronze',
  SILVER = 'silver',
  GOLD = 'gold',
  PLATINUM = 'platinum',
  DIAMOND = 'diamond'
}

// Transaction Status Enum
export enum TransactionStatus {
  PENDING = 'pending',
  SUCCESS = 'success',
  FAILED = 'failed',
  CANCELLED = 'cancelled'
}

// Notification Priority Enum
export enum NotificationPriority {
  LOW = 'low',
  NORMAL = 'normal',
  HIGH = 'high',
  URGENT = 'urgent'
}

// Gender Enum
export enum Gender {
  MALE = 'male',
  FEMALE = 'female',
  OTHER = 'other'
}