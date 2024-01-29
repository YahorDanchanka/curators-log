import { BaseTable } from '@/types'

export interface StudentEmploymentTable extends BaseTable {
  first_semester?: string | null
  second_semester?: string | null
  student_id: number
  course_id: number
}
