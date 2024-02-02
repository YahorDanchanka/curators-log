import { ReportTable } from '@/types'

export type ReportFormModelItem = Pick<
  ReportTable,
  'content' | 'hours_per_week' | 'hours_saturday' | 'month' | 'week'
> & {
  id: string | number
}

export type ReportFormModel = ReportFormModelItem[]
