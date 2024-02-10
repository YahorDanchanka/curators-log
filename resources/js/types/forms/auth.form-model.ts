import { UserTable } from '@/types'

export type AuthFormModel = Pick<UserTable, 'login' | 'password'>
