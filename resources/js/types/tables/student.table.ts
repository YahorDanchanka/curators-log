import { BaseTable } from '@/types'

export interface StudentTable extends BaseTable {
  surname: string
  name: string
  patronymic: string | null
  birthday: string | null
  group_id: number
}
