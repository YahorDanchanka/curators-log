import { PassportTable } from '@/types'

export type PassportFormModel = Pick<PassportTable, 'series' | 'number' | 'district_department' | 'issue_date'>
