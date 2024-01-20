import { CourseTable, CuratorModel } from '@/types'

export type CourseModel = CourseTable & {
  curator?: CuratorModel
  group_name?: string
}
