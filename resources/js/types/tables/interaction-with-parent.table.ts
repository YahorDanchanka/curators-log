import { BaseTable } from '@/types'

export interface InteractionWithParentTable extends BaseTable {
  date: string
  content: string
  result: string | null
  group_id: number
}
