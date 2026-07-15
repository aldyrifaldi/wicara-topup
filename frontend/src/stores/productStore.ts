import { create } from 'zustand';
import type { Product, Category } from '@/types';

interface ProductState {
  products: Product[];
  categories: Category[];
  filters: {
    category: string;
    search: string;
    minPrice: number;
    maxPrice: number;
  };
  setProducts: (products: Product[]) => void;
  setCategories: (categories: Category[]) => void;
  setFilters: (filters: Partial<ProductState['filters']>) => void;
}

export const useProductStore = create<ProductState>((set) => ({
  products: [],
  categories: [],
  filters: {
    category: '',
    search: '',
    minPrice: 0,
    maxPrice: 10000000,
  },
  setProducts: (products) => set({ products }),
  setCategories: (categories) => set({ categories }),
  setFilters: (filters) =>
    set((state) => ({ filters: { ...state.filters, ...filters } })),
}));