import { BaseTable } from '@/types'

export interface CuratorTable extends BaseTable {
  surname: string
  name: string
  patronymic: string | null
  user_id: number
}
