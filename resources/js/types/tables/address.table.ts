import { BaseTable } from '@/types'

export interface AddressTable extends BaseTable {
  type: string
  residence: string
  street: string
  region_id: number
  district_id: number
}
