import {
  CharacteristicStudentTable,
  CharacteristicTable,
  RelativeModel,
  StudentEmploymentTable,
  StudentTable,
} from '@/types'

export type StudentModel = StudentTable & {
  characteristics?: (CharacteristicTable & { pivot: CharacteristicStudentTable })[]
  employments?: StudentEmploymentTable[]
  relatives?: RelativeModel[]
  adult_relatives: RelativeModel[]
  minor_relatives: RelativeModel[]
  full_name?: string
  initials?: string
  is_nonresident?: boolean
  is_dorm?: boolean
}
