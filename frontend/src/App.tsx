import { useEffect } from 'react'
import { RouterProvider } from 'react-router-dom'
import { QueryClientProvider } from '@tanstack/react-query'
import { router } from '@/router'
import { queryClient } from '@/lib/queryClient'
import { useAuthStore } from '@/stores/authStore'

function ThemeApplier() {
  const user = useAuthStore((s) => s.user)
  useEffect(() => {
    const isDark = user?.ui_preferences?.dark_mode ?? false
    document.documentElement.classList.toggle('dark', isDark)
  }, [user])
  return null
}

export default function App() {
  return (
    <QueryClientProvider client={queryClient}>
      <ThemeApplier />
      <RouterProvider router={router} />
    </QueryClientProvider>
  )
}
