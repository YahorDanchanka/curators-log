import { CharacteristicStudentTable, EducationLevelTable } from '@/types'

export type EducationLevelModel = EducationLevelTable & {
  characteristic_student?: CharacteristicStudentTable
}
