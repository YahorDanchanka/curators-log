import { UserTable, WithPolicyAccessors } from '@/types'

export type UserModel = UserTable &
  WithPolicyAccessors & {
    is_admin?: boolean
  }
