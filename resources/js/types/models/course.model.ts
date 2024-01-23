import { CourseTable, CuratorModel, ExpulsionTable } from '@/types'

export type CourseModel = CourseTable & {
  group_name?: string
  curator?: CuratorModel
  expulsions?: ExpulsionTable[]
}
