import { BaseTable } from '@/types'

export interface CharacteristicStudentTable extends BaseTable {
  student_id: number
  characteristic_id: number
  course_id: number
}
