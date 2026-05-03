import { createBrowserRouter, Navigate } from 'react-router-dom'
import { AuthGuard } from './guards/AuthGuard'
import { AppLayout } from '@/components/layout/AppLayout'
import { LoginPage } from '@/pages/auth/LoginPage'
import { RegisterPage } from '@/pages/auth/RegisterPage'
import { DashboardPage } from '@/pages/dashboard/DashboardPage'
import { TaskListPage } from '@/pages/practice/TaskListPage'
import { TaskPracticePage } from '@/pages/practice/TaskPracticePage'
import { SessionResultsPage } from '@/pages/practice/SessionResultsPage'

export const router = createBrowserRouter([
  { path: '/login',    element: <LoginPage /> },
  { path: '/register', element: <RegisterPage /> },
  {
    element: <AuthGuard />,
    children: [
      {
        element: <AppLayout />,
        children: [
          { index: true,                       element: <Navigate to="/dashboard" replace /> },
          { path: '/dashboard',                element: <DashboardPage /> },
          { path: '/practice',                 element: <TaskListPage /> },
          { path: '/practice/:taskUlid',       element: <TaskPracticePage /> },
          { path: '/results/:attemptUlid',     element: <SessionResultsPage /> },
        ],
      },
    ],
  },
  { path: '*', element: <Navigate to="/" replace /> },
])
