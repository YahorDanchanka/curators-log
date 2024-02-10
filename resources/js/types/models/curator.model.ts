import { CuratorTable, UserTable } from '@/types'

export type CuratorModel = CuratorTable & {
  full_name?: string
  initials?: string
  user?: UserTable
}
