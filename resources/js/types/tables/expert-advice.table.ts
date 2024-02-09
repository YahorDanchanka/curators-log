import { BaseTable } from '@/types'

export interface ExpertAdviceTable extends BaseTable {
  content: string
  result: string
  course_id: number
}
