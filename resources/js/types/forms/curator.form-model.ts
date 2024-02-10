import { AuthFormModel, CuratorTable } from '@/types'

export type CuratorFormModel = Pick<CuratorTable, 'surname' | 'name' | 'patronymic'> & AuthFormModel
