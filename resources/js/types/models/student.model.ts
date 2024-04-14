import {
  AddressModel,
  AsocialBehaviorTable,
  CharacteristicStudentTable,
  CharacteristicTable,
  ExpertAdviceTable,
  ExpulsionTable,
  IndividualWorkTable,
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
  asocial_behavior?: AsocialBehaviorTable[]
  expert_advice?: ExpertAdviceTable[]
  individual_work?: IndividualWorkTable[]
  student_individual_work?: IndividualWorkTable[]
  relative_individual_work?: IndividualWorkTable[]
  expulsion?: ExpulsionTable | null
}
