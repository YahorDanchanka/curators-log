import {
  AddressModel,
  CharacteristicStudentTable,
  CharacteristicTable,
  PassportModel,
  RelativeModel,
  StudentAchievementTable,
  StudentEmploymentTable,
  StudentTable,
} from '@/types'

export type StudentModel = StudentTable & {
  characteristics?: (CharacteristicTable & { pivot: CharacteristicStudentTable })[]
  employments?: StudentEmploymentTable[]
  relatives?: RelativeModel[]
  adult_relatives?: RelativeModel[]
  minor_relatives?: RelativeModel[]
  father?: RelativeModel
  mother?: RelativeModel
  address?: AddressModel
  study_address?: AddressModel
  passport?: PassportModel
  age?: number
  full_name?: string
  initials?: string
  is_nonresident?: boolean
  is_dorm?: boolean
  achievements?: StudentAchievementTable[]
}
