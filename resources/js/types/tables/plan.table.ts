import { BaseTable, Month } from '@/types'

export interface PlanTable extends BaseTable {
  start_date: string
  end_date: string | null
  content: string
  done: 0 | 1
  section: 'DIAGNOSTIC' | 'METHODIC' | 'ORGANIZATIONAL_PEDAGOGICAL'
  month: Month
  course_id: number
}
