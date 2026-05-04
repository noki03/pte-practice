import { describe, it, expect, vi } from 'vitest'
import { render, screen } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { Button } from './button'

describe('Button', () => {
  it('renders children', () => {
    render(<Button>Click me</Button>)
    expect(screen.getByRole('button', { name: 'Click me' })).toBeInTheDocument()
  })

  it('calls onClick when clicked', async () => {
    const handler = vi.fn()
    render(<Button onClick={handler}>Click</Button>)
    await userEvent.click(screen.getByRole('button'))
    expect(handler).toHaveBeenCalledOnce()
  })

  it('is disabled when disabled prop is set', () => {
    render(<Button disabled>Disabled</Button>)
    expect(screen.getByRole('button')).toBeDisabled()
  })

  it('does not call onClick when disabled', async () => {
    const handler = vi.fn()
    render(<Button disabled onClick={handler}>Disabled</Button>)
    await userEvent.click(screen.getByRole('button'))
    expect(handler).not.toHaveBeenCalled()
  })

  it('applies variant class', () => {
    render(<Button variant="destructive">Delete</Button>)
    expect(screen.getByRole('button')).toHaveClass('bg-destructive')
  })

  it('renders child element when asChild is true', () => {
    render(
      <Button asChild>
        <a href="/test">Link button</a>
      </Button>,
    )
    const link = screen.getByRole('link', { name: 'Link button' })
    expect(link).toBeInTheDocument()
    expect(link).toHaveClass('bg-primary')
  })
})
