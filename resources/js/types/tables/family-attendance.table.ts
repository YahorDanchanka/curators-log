import { BaseTable } from '@/types'

export interface FamilyAttendanceTable extends BaseTable {
  note: string | null
  student_id: number
  relative_id: number | null
}
