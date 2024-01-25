import { CharacteristicStudentTable } from '@/types'

export interface LeadershipFormModel {
  leader_id?: number | null
  deputy_leader_id?: number | null
  brsm_secretary_id?: number | null
  union_organizer_id?: number | null
  group_composition: (Pick<CharacteristicStudentTable, 'characteristic_id' | 'course_id'> & {
    student_id: number | null
  })[]
}
