import { createBrowserRouter, Navigate } from 'react-router-dom'
import { AuthGuard } from './guards/AuthGuard'
import { DashboardLayout } from '@/pages/dashboard/DashboardLayout'
import { DashboardHome } from '@/pages/dashboard/DashboardHome'
import { LoginPage } from '@/pages/auth/LoginPage'
import { RegisterPage } from '@/pages/auth/RegisterPage'
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
        element: <DashboardLayout />,
        children: [
          { index: true,                       element: <Navigate to="/dashboard" replace /> },
          { path: '/dashboard',                element: <DashboardHome /> },
          { path: '/practice',                 element: <TaskListPage /> },
          { path: '/practice/:taskUlid',       element: <TaskPracticePage /> },
          { path: '/results/:attemptUlid',     element: <SessionResultsPage /> },
        ],
      },
    ],
  },
  { path: '*', element: <Navigate to="/" replace /> },
])
