import { CourseModel, GroupTable, StudentModel } from '@/types'

export type GroupModel = GroupTable & {
  name?: string | null
  courses?: CourseModel[]
  current_course?: CourseModel | null
  students?: StudentModel[]
}
