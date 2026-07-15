import {
  OrderStatus,
  PaymentMethod,
  UserRole,
  Gender,
  Level,
} from './enums'
import {
  User,
  Product,
  Category,
  Order,
  Balance,
  AppNotification,
  Paginated,
  CartItem,
  Tutorial,
  FAQ,
  ContactMessage,
  RewardItem,
  LeaderboardEntry,
  PointsTransaction,
  BalanceTransaction,
} from './models'

// ==================== Auth Types ====================

export interface LoginRequest {
  email: string
  password: string
  remember: boolean
}

export interface RegisterRequest {
  name: string
  email: string
  phone: string | null
  password: string
  passwordConfirmation: string
  username: string | null
  referralCode: string | null
}

export interface ForgotPasswordRequest {
  email: string
}

export interface ResetPasswordRequest {
  token: string
  email: string
  password: string
  passwordConfirmation: string
}

export interface VerifyOTPRequest {
  email: string
  code: string
  type: 'email' | 'phone'
}

export interface AuthResponse {
  user: User
  token: string
  expiresIn: number
}

// ==================== Product Types ====================

export interface ProductFilters {
  category?: number
  search?: string
  status?: string
  featured?: boolean
  popular?: boolean
  new?: boolean
  sort?: 'name' | 'price' | 'created_at' | 'popular'
  order?: 'asc' | 'desc'
  page?: number
  perPage?: number
}

export interface ProductResponse {
  product: Product
  relatedProducts?: Product[]
}

export interface ProductsResponse extends Paginated<Product> {}

export interface FeaturedProductsResponse {
  featured: Product[]
  popular: Product[]
  new: Product[]
}

export interface CategoryResponse {
  category: Category
  products?: Paginated<Product>
}

export interface CategoriesResponse extends Paginated<Category> {}

// ==================== Order Types ====================

export interface CreateOrderRequest {
  items: {
    subProductId: number
    quantity: number
  }[]
  paymentMethod: PaymentMethod
  bankId?: number
  notes?: string
  couponCode?: string
}

export interface OrderResponse {
  order: Order
}

export interface OrdersResponse extends Paginated<Order> {}

export interface OrderDetailResponse {
  order: Order
  statusHistory?: any[]
}

// ==================== User Types ====================

export interface UpdateProfileRequest {
  name?: string
  phone?: string
  username?: string
  gender?: Gender
  dateOfBirth?: string
  avatar?: File | string
}

export interface ChangePasswordRequest {
  currentPassword: string
  password: string
  passwordConfirmation: string
}

export interface ProfileResponse {
  user: User
}

export interface BalanceResponse {
  balance: Balance
  transactions: Paginated<BalanceTransaction>
}

export interface NotificationsResponse extends Paginated<AppNotification> {}

export interface MarkNotificationReadRequest {
  notificationIds: number[]
}

// ==================== Cart Types ====================

export interface AddToCartRequest {
  productId: number
  subProductId: number
  quantity: number
  server?: string
  accountId?: string
}

export interface UpdateCartRequest {
  quantity: number
}

export interface ApplyCouponRequest {
  code: string
}

// ==================== Payment Types ====================

export interface TopUpRequest {
  amount: number
  paymentMethod: PaymentMethod
  bankId?: number
}

export interface TopUpResponse {
  transaction: {
    id: number
    amount: number
    paymentMethod: PaymentMethod
    status: string
    expiresAt: string
    paymentDetails: {
      bankName: string | null
      accountNumber: string | null
      accountHolder: string | null
      qrCode: string | null
      vaNumber: string | null
    }
  }
}

export interface CheckPaymentStatusResponse {
  status: string
  paidAt: string | null
}

// ==================== Rewards Types ====================

export interface RewardsListResponse extends Paginated<RewardItem> {}

export interface RedeemRewardRequest {
  rewardId: number
}

export interface PointsHistoryResponse extends Paginated<PointsTransaction> {}

export interface LeaderboardResponse {
  leaderboard: LeaderboardEntry[]
  userRank: number | null
  userPoints: number
}

// ==================== Content Types ====================

export interface FAQListResponse extends Paginated<FAQ> {}

export interface TutorialListResponse extends Paginated<Tutorial> {}

export interface ContactSupportRequest {
  name: string
  email: string
  phone?: string
  subject: string
  message: string
}

// ==================== Admin Types ====================

export interface AdminDashboardResponse {
  stats: {
    totalRevenue: number
    totalOrders: number
    totalUsers: number
    totalProducts: number
    pendingOrders: number
    completedOrders: number
    cancelledOrders: number
    todayRevenue: number
    todayOrders: number
    todayUsers: number
  }
  recentOrders: Order[]
  topProducts: any[]
  revenueChart: any[]
}

export interface UpdateOrderStatusRequest {
  status: OrderStatus
  note?: string
}

export interface CreateProductRequest {
  categoryId: number
  name: string
  slug: string
  description: string
  shortDescription?: string
  image?: File | string
  gallery?: (File | string)[]
  type: string
  status: string
  isFeatured: boolean
  isPopular: boolean
  isNew: boolean
  sortOrder: number
  subProducts: {
    name: string
    description?: string
    price: number
    discountedPrice?: number
    sku: string
    stock: number
    server?: string
    denomination?: string
  }[]
}

export interface UpdateProductRequest extends Partial<CreateProductRequest> {}

export interface CreateCategoryRequest {
  name: string
  slug: string
  description?: string
  image?: File | string
  icon?: string
  parentId?: number
  order: number
  isActive: boolean
}

export interface UpdateCategoryRequest extends Partial<CreateCategoryRequest> {}

export interface UsersListParams {
  search?: string
  role?: UserRole
  status?: string
  page?: number
  perPage?: number
}

export interface UsersListResponse extends Paginated<User> {}

// ==================== Generic Types ====================

export interface ApiResponse<T = any> {
  success: boolean
  message?: string
  data?: T
  errors?: Record<string, string[]>
}

export interface ValidationError {
  field: string
  message: string
}

export interface ErrorResponse {
  success: false
  message: string
  errors?: ValidationError[]
}

// ==================== Search Types ====================

export interface SearchRequest {
  query: string
  type?: 'products' | 'categories' | 'tutorials' | 'faq' | 'all'
  page?: number
  perPage?: number
}

export interface SearchResponse {
  products: Product[]
  categories: Category[]
  tutorials: Tutorial[]
  faq: FAQ[]
  totalResults: number
}

// ==================== Form Options Types ====================

export interface FormOptions {
  banks: {
    id: number
    name: string
    accountNumber: string
    accountHolder: string
  }[]
  paymentMethods: {
    method: PaymentMethod
    name: string
    icon?: string
    fee: number
    minAmount: number
    maxAmount: number
  }[]
  categories: Category[]
  levels: Level[]
}

// ==================== Statistics Types ====================

export interface UserStatsResponse {
  totalOrders: number
  totalSpent: number
  currentPoints: number
  currentLevel: Level
  nextLevel: Level | null
  pointsToNextLevel: number
  completedPercentage: number
}

export interface SystemStatsResponse {
  activeUsers: number
  totalTransactions: number
  systemStatus: 'operational' | 'degraded' | 'down'
  maintenanceMode: boolean
}
