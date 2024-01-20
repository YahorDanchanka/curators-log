import { CharacteristicStudentTable, CharacteristicTable, StudentTable } from '@/types'

export type StudentModel = StudentTable & {
  full_name?: string
  initials?: string
  characteristics?: (CharacteristicTable & { pivot: CharacteristicStudentTable })[]
  is_nonresident?: boolean
  is_dorm?: boolean
}
