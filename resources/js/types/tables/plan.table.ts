import { BaseTable } from '@/types'

export interface PlanTable extends BaseTable {
  start_date: string
  end_date: string | null
  content: string
  done: 0 | 1
  section: 'DIAGNOSTIC' | 'METHODIC' | 'ORGANIZATIONAL_PEDAGOGICAL'
  month: '1' | '2' | '3' | '4' | '5' | '6' | '7' | '8' | '9' | '10' | '11' | '12'
  course_id: number
}
