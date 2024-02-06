import { BaseTable } from '@/types'

export interface FamilyAttendanceRowTable extends BaseTable {
  date: string
  value: 0 | 1
  family_attendance_id: number
  course_id: number
}
