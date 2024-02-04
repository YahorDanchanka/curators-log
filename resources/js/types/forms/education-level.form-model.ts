import { EducationLevelTable } from '@/types'

export type EducationLevelFormModelItem = Pick<EducationLevelTable, 'level'> & {
  id: string | number
  characteristic_student: {
    id: string | number
    student_id: number
    characteristic_id: number
  }
}

export type EducationLevelFormModel = EducationLevelFormModelItem[]
