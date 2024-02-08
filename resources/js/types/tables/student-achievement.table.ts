import { BaseTable } from '@/types'

export interface StudentAchievementTable extends BaseTable {
  date: string
  reason: string
  prize: string
  student_id: number
}
