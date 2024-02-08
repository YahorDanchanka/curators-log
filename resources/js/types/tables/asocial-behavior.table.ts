import { BaseTable } from '@/types'

export interface AsocialBehaviorTable extends BaseTable {
  date: string
  action: string
  sanctions: string | null
  result: string | null
  student_id: number
}
