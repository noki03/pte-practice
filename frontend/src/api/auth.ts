import { apiClient } from './client'
import type { User } from '@/types/attempt'

export interface LoginPayload  { email: string; password: string }
export interface RegisterPayload { name: string; email: string; password: string; password_confirmation: string; timezone?: string }

export const authApi = {
  register: (data: RegisterPayload) =>
    apiClient.post<{ user: User; token: string; message: string }>('/auth/register', data),

  login: (data: LoginPayload) =>
    apiClient.post<{ user: User; token: string }>('/auth/login', data),

  logout: () =>
    apiClient.post('/auth/logout'),

  me: () =>
    apiClient.get<{ user: User }>('/auth/me'),
}
