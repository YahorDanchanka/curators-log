import { CourseFormModel } from '@/types'

export interface GroupFormModel {
  number: number
  specialty_id: number | null
  courses: (CourseFormModel & { id: string | number })[]
}
