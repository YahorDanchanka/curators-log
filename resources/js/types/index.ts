export interface IErrors {
  [key: string | number]: string
}

export type IEnum = IEnumKey[]

export interface IEnumKey {
  name: string
  value: string | number
}

export type Month = '1' | '2' | '3' | '4' | '5' | '6' | '7' | '8' | '9' | '10' | '11' | '12'

export * from './api'
export * from './components'
export * from './forms'
export * from './models'
export * from './tables'
