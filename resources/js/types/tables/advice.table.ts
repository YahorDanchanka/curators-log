import { BaseTable } from '@/types'

export interface AdviceTable extends BaseTable {
  date: string
  comments: string | null
  suggestions: string | null
  full_name: string | null
  position: string | null
  group_id: number
}
