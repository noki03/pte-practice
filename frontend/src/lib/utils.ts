import { type ClassValue, clsx } from 'clsx'
import { twMerge } from 'tailwind-merge'

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs))
}

export function formatDuration(seconds: number): string {
  const m = Math.floor(seconds / 60)
  const s = seconds % 60
  return m > 0 ? `${m}m ${s}s` : `${s}s`
}

export function formatMs(ms: number): string {
  return formatDuration(Math.round(ms / 1000))
}

export function scoreToColor(score: number): string {
  if (score >= 79) return 'text-emerald-500'
  if (score >= 65) return 'text-blue-500'
  if (score >= 50) return 'text-amber-500'
  return 'text-red-500'
}
