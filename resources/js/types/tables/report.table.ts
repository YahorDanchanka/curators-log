import { BaseTable, Month } from '@/types'

export interface ReportTable extends BaseTable {
  content: string
  hours_per_week: number
  hours_saturday: number | null
  month: Month
  week: string
  course_id: number
}
