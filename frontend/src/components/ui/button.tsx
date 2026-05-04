import { Children, cloneElement, forwardRef, isValidElement, type ButtonHTMLAttributes } from 'react'
import { cva, type VariantProps } from 'class-variance-authority'
import { cn } from '@/lib/utils'

const buttonVariants = cva(
  'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50',
  {
    variants: {
      variant: {
        default:     'bg-primary text-primary-fg hover:bg-primary/90',
        destructive: 'bg-destructive text-destructive-fg hover:bg-destructive/90',
        outline:     'border border-border bg-background hover:bg-secondary hover:text-secondary-fg',
        secondary:   'bg-secondary text-secondary-fg hover:bg-secondary/80',
        ghost:       'hover:bg-secondary hover:text-secondary-fg',
        link:        'text-primary underline-offset-4 hover:underline',
      },
      size: {
        default: 'h-10 px-4 py-2',
        sm:      'h-9 rounded-sm px-3 text-xs',
        lg:      'h-11 rounded-lg px-8',
        icon:    'h-10 w-10',
      },
    },
    defaultVariants: { variant: 'default', size: 'default' },
  },
)

export interface ButtonProps
  extends ButtonHTMLAttributes<HTMLButtonElement>,
    VariantProps<typeof buttonVariants> {
  asChild?: boolean
}

const Button = forwardRef<HTMLButtonElement, ButtonProps>(
  ({ className, variant, size, asChild = false, children, ...props }, ref) => {
    const cls = cn(buttonVariants({ variant, size }), className)

    if (asChild && isValidElement(children)) {
      const child = Children.only(children) as React.ReactElement<Record<string, unknown>>
      return cloneElement(child, {
        ...props,
        className: cn(cls, child.props['className'] as string | undefined),
      })
    }

    return (
      <button ref={ref} className={cls} {...props}>
        {children}
      </button>
    )
  },
)
Button.displayName = 'Button'

export { Button, buttonVariants }
