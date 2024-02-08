import { BaseTable } from '@/types'

export interface GroupAchievementTable extends BaseTable {
  content: string
  semester: '1' | '2'
  course_id: number
}
