import { BaseTable } from '@/types'

export interface AdministrativeDivisionTable extends BaseTable {
  type: 'region' | 'district'
  name: string
  parent_id: number | null
}
