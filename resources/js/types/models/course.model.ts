import { CourseTable, CuratorModel, ExpulsionTable, PlanTable } from '@/types'

export type CourseModel = CourseTable & {
  group_name?: string
  curator?: CuratorModel
  expulsions?: ExpulsionTable[]
  plans?: PlanTable[]
}
