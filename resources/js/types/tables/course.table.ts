import { BaseTable } from '@/types'

export interface CourseTable extends BaseTable {
  number: number
  start_education: string
  end_education: string
  curator_id: number
  group_id: number
}
