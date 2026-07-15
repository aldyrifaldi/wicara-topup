import api from '@/lib/api';
import { LoginRequest, RegisterRequest, User } from '@/types';

export const authApi = {
  login: (data: LoginRequest) => api.post('/auth/login', data),
  register: (data: RegisterRequest) => api.post('/auth/register', data),
  logout: () => api.post('/auth/logout'),
  me: () => api.get('/auth/me'),
  forgotPassword: (data: { email: string }) => api.post('/auth/forgot-password', data),
  resetPassword: (data: { token: string; password: string }) => api.post('/auth/reset-password', data),
};

export const productApi = {
  getProducts: (params?: { page?: number; category?: string; search?: string }) =>
    api.get('/products', { params }),
  getProduct: (slug: string) => api.get(`/products/${slug}`),
  getDenominations: (slug: string) => api.get(`/products/${slug}/denominations`),
  getFeatured: () => api.get('/products?featured=true'),
  search: (query: string) => api.get('/products', { params: { search: query } }),
};

export const categoryApi = {
  getCategories: () => api.get('/categories'),
  getCategory: (slug: string) => api.get(`/categories/${slug}`),
};

export const orderApi = {
  getOrders: (params?: { page?: number; status?: string }) =>
    api.get('/orders', { params }),
  getOrder: (code: string) => api.get(`/orders/${code}`),
  createOrder: (data: any) => api.post('/orders', data),
  cancelOrder: (code: string) => api.delete(`/orders/${code}`),
};

export const userApi = {
  getProfile: () => api.get('/user/profile'),
  updateProfile: (data: Partial<User>) => api.put('/user/profile', data),
  getBalance: () => api.get('/user/balance'),
  getOrders: (params?: { page?: number }) => api.get('/user/orders', { params }),
  getPoints: () => api.get('/user/points'),
  getNotifications: () => api.get('/user/notifications'),
  getLeaderboard: () => api.get('/user/leaderboard'),
};