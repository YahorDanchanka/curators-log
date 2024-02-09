import { BaseTable } from '@/types'

export interface IndividualWorkTable extends BaseTable {
  date: string
  content: string
  result: string | null
  type: 'relative' | 'student'
  student_id: number
}
