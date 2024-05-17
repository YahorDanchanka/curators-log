import { UserTable } from '@/types'

export type UserFormModel = Pick<UserTable, 'login' | 'password'> & { role: string | null }
