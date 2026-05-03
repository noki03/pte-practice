import { describe, it, expect } from 'vitest'
import { render, screen } from '@testing-library/react'
import { RecordingTimer } from './RecordingTimer'

describe('RecordingTimer', () => {
  it('displays remaining time in MM:SS format', () => {
    render(<RecordingTimer elapsedMs={5_000} remainingMs={35_000} maxMs={40_000} />)
    expect(screen.getByText('00:35')).toBeInTheDocument()
  })

  it('displays elapsed time', () => {
    render(<RecordingTimer elapsedMs={10_000} remainingMs={30_000} maxMs={40_000} />)
    expect(screen.getByText('00:10 elapsed')).toBeInTheDocument()
  })

  it('shows 00:00 when no time remaining', () => {
    render(<RecordingTimer elapsedMs={40_000} remainingMs={0} maxMs={40_000} />)
    expect(screen.getByText('00:00')).toBeInTheDocument()
  })
})
