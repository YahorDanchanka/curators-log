import { FamilyAttendanceRowTable, FamilyAttendanceTable } from '@/types'

export type FamilyAttendanceModel = FamilyAttendanceTable & {
  rows?: FamilyAttendanceRowTable[]
}
