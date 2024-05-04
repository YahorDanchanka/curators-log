import { BaseTable } from '@/types'

export interface AbsenceTable extends BaseTable {
  date: string
  reasonable_count: number
  unreasonable_count: number
  student_id: number
}
