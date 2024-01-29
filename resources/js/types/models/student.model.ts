import { CharacteristicStudentTable, CharacteristicTable, StudentEmploymentTable, StudentTable } from '@/types'

export type StudentModel = StudentTable & {
  characteristics?: (CharacteristicTable & { pivot: CharacteristicStudentTable })[]
  employments?: StudentEmploymentTable[]
  full_name?: string
  initials?: string
  is_nonresident?: boolean
  is_dorm?: boolean
}
