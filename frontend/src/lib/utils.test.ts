import { describe, it, expect } from 'vitest'
import { cn, formatDuration, formatMs, scoreToColor } from './utils'

describe('cn', () => {
  it('merges class names', () => {
    expect(cn('foo', 'bar')).toBe('foo bar')
  })

  it('overrides conflicting Tailwind classes', () => {
    expect(cn('p-2', 'p-4')).toBe('p-4')
  })

  it('ignores falsy values', () => {
    expect(cn('foo', false, undefined, null, '')).toBe('foo')
  })
})

describe('formatDuration', () => {
  it('formats seconds only', () => {
    expect(formatDuration(45)).toBe('45s')
  })

  it('formats minutes and seconds', () => {
    expect(formatDuration(90)).toBe('1m 30s')
  })

  it('formats exactly 60 seconds as 1m 0s', () => {
    expect(formatDuration(60)).toBe('1m 0s')
  })
})

describe('formatMs', () => {
  it('converts milliseconds to duration string', () => {
    expect(formatMs(45_000)).toBe('45s')
    expect(formatMs(90_500)).toBe('1m 31s')
  })
})

describe('scoreToColor', () => {
  it('returns green for high scores', () => {
    expect(scoreToColor(85)).toBe('text-emerald-500')
  })

  it('returns blue for good scores', () => {
    expect(scoreToColor(70)).toBe('text-blue-500')
  })

  it('returns amber for passing scores', () => {
    expect(scoreToColor(55)).toBe('text-amber-500')
  })

  it('returns red for low scores', () => {
    expect(scoreToColor(30)).toBe('text-red-500')
  })
})
