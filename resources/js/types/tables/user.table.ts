import { BaseTable } from '@/types'

export interface UserTable extends BaseTable {
  login: string
  password: string
  remember_token: string | null
}
