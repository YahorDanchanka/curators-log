import { CuratorTable } from '@/types'

export type CuratorModel = CuratorTable & {
  full_name?: string
  initials?: string
}
