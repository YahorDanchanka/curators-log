import { BaseTable } from '@/types'

export interface PassportTable extends BaseTable {
  series: string
  number: string
  district_department: string
  issue_date: string
}
