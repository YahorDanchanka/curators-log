import { CourseTable, CuratorModel, ExpulsionTable, GroupAchievementTable, PlanTable, ReportTable } from '@/types'

export type CourseModel = CourseTable & {
  group_name?: string
  curator?: CuratorModel
  expulsions?: ExpulsionTable[]
  plans?: PlanTable[]
  reports?: ReportTable[]
  achievements?: GroupAchievementTable[]
}
