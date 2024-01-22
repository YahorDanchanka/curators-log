import { BaseTable } from '@/types'

export interface ExpulsionTable extends BaseTable {
  date: string
  initiator: 'student' | 'college'
  reason: string | null
  student_id: number
  course_id: number
}
