import { AdviceTable, CourseModel, GroupTable, InteractionWithParentTable, StudentModel } from '@/types'

export type GroupModel = GroupTable & {
  name?: string | null
  education_period?: string | null
  courses?: CourseModel[]
  current_course?: CourseModel | null
  students?: StudentModel[]
  interaction_with_parents?: InteractionWithParentTable[]
  first_course?: CourseModel
  last_course?: CourseModel
  advice?: AdviceTable[]
}
