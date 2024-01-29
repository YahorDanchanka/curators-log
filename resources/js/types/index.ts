export interface IErrors {
  [key: string | number]: string
}

export type IEnum = IEnumKey[]

export interface IEnumKey {
  name: string
  value: string | number
}

export * from './forms'
export * from './models'
export * from './tables'
