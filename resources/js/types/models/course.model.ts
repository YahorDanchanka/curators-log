import { CourseTable, CuratorModel, ExpulsionTable, PlanTable, ReportTable } from '@/types'

export type CourseModel = CourseTable & {
  group_name?: string
  curator?: CuratorModel
  expulsions?: ExpulsionTable[]
  plans?: PlanTable[]
  reports?: ReportTable[]
}
