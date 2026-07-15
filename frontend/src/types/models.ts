import {
  OrderStatus,
  PaymentMethod,
  UserRole,
  TransactionType,
  TransactionStatus,
  NotificationType,
  NotificationPriority,
  ProductStatus,
  DiscountType,
  Level,
  Gender,
} from './enums'

// User Model
export interface User {
  id: number
  name: string
  email: string
  username: string | null
  phone: string | null
  avatar: string | null
  gender: Gender | null
  dateOfBirth: string | null
  role: UserRole
  level: Level
  points: number
  balance: number
  emailVerifiedAt: string | null
  phoneVerifiedAt: string | null
  isActive: boolean
  lastLoginAt: string | null
  createdAt: string
  updatedAt: string
}

// Category Model
export interface Category {
  id: number
  name: string
  slug: string
  description: string | null
  image: string | null
  icon: string | null
  parentId: number | null
  order: number
  isActive: boolean
  productsCount?: number
  createdAt: string
  updatedAt: string
}

// SubProduct Model
export interface SubProduct {
  id: number
  productId: number
  name: string
  description: string | null
  price: number
  discountedPrice: number | null
  sku: string
  stock: number
  server: string | null
  denomination: string | null
  isActive: boolean
  createdAt: string
  updatedAt: string
}

// Product Model
export interface Product {
  id: number
  categoryId: number
  name: string
  slug: string
  description: string
  shortDescription: string | null
  image: string | null
  gallery: string[]
  type: string
  status: ProductStatus
  isFeatured: boolean
  isPopular: boolean
  isNew: boolean
  sortOrder: number
  metaTitle: string | null
  metaDescription: string | null
  category?: Category
  subProducts?: SubProduct[]
  createdAt: string
  updatedAt: string
}

// Order Item Model
export interface OrderItem {
  id: number
  orderId: number
  subProductId: number
  product: string
  subProductName: string
  quantity: number
  price: number
  subtotal: number
  subProduct?: SubProduct
}

// Payment Model
export interface Payment {
  id: number
  orderId: number
  paymentMethod: PaymentMethod
  amount: number
  status: TransactionStatus
  transactionId: string | null
  reference: string
  paidAt: string | null
  expiresAt: string | null
  bankName: string | null
  accountNumber: string | null
  accountHolder: string | null
  qrCode: string | null
  vaNumber: string | null
  createdAt: string
  updatedAt: string
}

// Order Model
export interface Order {
  id: number
  orderNumber: string
  userId: number
  status: OrderStatus
  totalAmount: number
  subtotal: number
  discount: number
  fee: number
  notes: string | null
  user?: User
  items?: OrderItem[]
  payment?: Payment
  statusHistory?: OrderStatusHistory[]
  createdAt: string
  updatedAt: string
}

// Order Status History Model
export interface OrderStatusHistory {
  id: number
  orderId: number
  status: OrderStatus
  note: string | null
  createdBy: number
  createdAt: string
}

// Balance Model
export interface Balance {
  id: number
  userId: number
  amount: number
  pending: number
  total: number
  updatedAt: string
}

// Balance Transaction Model
export interface BalanceTransaction {
  id: number
  balanceId: number
  userId: number
  type: TransactionType
  amount: number
  balanceBefore: number
  balanceAfter: number
  description: string
  reference: string | null
  status: TransactionStatus
  createdAt: string
}

// Reward Points Model
export interface RewardPoints {
  id: number
  userId: number
  points: number
  totalEarned: number
  totalSpent: number
  level: Level
  nextLevelPoints: number
  createdAt: string
  updatedAt: string
}

// Points Transaction Model
export interface PointsTransaction {
  id: number
  userId: number
  type: 'earn' | 'spend'
  amount: number
  balanceBefore: number
  balanceAfter: number
  description: string
  reference: string | null
  createdAt: string
}

// Reward Item Model
export interface RewardItem {
  id: number
  name: string
  description: string
  image: string | null
  pointsCost: number
  stock: number
  isActive: boolean
  createdAt: string
  updatedAt: string
}

// Notification Model
export interface AppNotification {
  id: number
  userId: number
  type: NotificationType
  priority: NotificationPriority
  title: string
  message: string
  data: Record<string, unknown> | null
  isRead: boolean
  readAt: string | null
  createdAt: string
}

// Cart Item Model
export interface CartItem {
  id: number | string
  productId: number
  subProductId: number
  productName: string
  subProductName: string
  image: string | null
  price: number
  quantity: number
  server: string | null
  accountId: string | null
}

// Leaderboard Entry Model
export interface LeaderboardEntry {
  rank: number
  userId: number
  name: string
  username: string | null
  avatar: string | null
  level: Level
  points: number
  totalSpent: number
  ordersCount: number
}

// Bank Model
export interface Bank {
  id: number
  name: string
  code: string
  logo: string | null
  accountNumber: string | null
  accountHolder: string | null
  isActive: boolean
}

// Payment Method Option Model
export interface PaymentMethodOption {
  id: number
  method: PaymentMethod
  name: string
  description: string | null
  icon: string | null
  fee: number
  feeType: DiscountType | null
  minAmount: number
  maxAmount: number
  isActive: boolean
}

// FAQ Model
export interface FAQ {
  id: number
  question: string
  answer: string
  category: string
  order: number
  isPublished: boolean
}

// Tutorial Model
export interface Tutorial {
  id: number
  title: string
  description: string
  content: string
  thumbnail: string | null
  videoUrl: string | null
  category: string
  duration: number | null
  order: number
  isPublished: boolean
  createdAt: string
  updatedAt: string
}

// Contact Message Model
export interface ContactMessage {
  id: number
  name: string
  email: string
  phone: string | null
  subject: string
  message: string
  isRead: boolean
  repliedAt: string | null
  createdAt: string
}

// Discount/Coupon Model
export interface Coupon {
  id: number
  code: string
  type: DiscountType
  value: number
  minPurchase: number
  maxDiscount: number | null
  usageLimit: number
  usedCount: number
  validFrom: string
  validUntil: string
  isActive: boolean
}

// Paginated Response Model
export interface Paginated<T> {
  data: T[]
  current_page: number
  last_page: number
  per_page: number
  total: number
  from: number | null
  to: number | null
  links: {
    url: string | null
    label: string
    active: boolean
  }[]
}

// Dashboard Stats Model
export interface DashboardStats {
  totalRevenue: number
  totalOrders: number
  totalUsers: number
  totalProducts: number
  pendingOrders: number
  completedOrders: number
  cancelledOrders: number
  recentOrders: Order[]
  topProducts: { product: Product; sales: number; revenue: number }[]
  revenueChart: { date: string; revenue: number; orders: number }[]
  userGrowthChart: { date: string; users: number }[]
}

// Settings Model
export interface Settings {
  siteName: string
  siteDescription: string
  logo: string | null
  contactEmail: string
  contactPhone: string
  address: string
  socialMedia: {
    facebook: string | null
    twitter: string | null
    instagram: string | null
    youtube: string | null
    tiktok: string | null
  }
  maintenanceMode: boolean
}
