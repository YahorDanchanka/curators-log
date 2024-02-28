import { PassportTable } from '@/types'

export type PassportFormModel = Pick<
  PassportTable,
  'series' | 'number' | 'id_number' | 'district_department' | 'issue_date'
>
