export interface FamilyAttendanceFormModelColumnRow {
  id: string | number
  value?: 0 | 1 | null
  student_id: number
}

export interface FamilyAttendanceFormModelColumn {
  id: string | number
  date?: string | null
  rows: FamilyAttendanceFormModelColumnRow[]
  course_id: number
}

export interface FamilyAttendanceFormModelItem {
  id: string | number
  note?: string | null
  student_id: number
  relative_id?: number | null
}

export type FamilyAttendanceFormModel = {
  columns: FamilyAttendanceFormModelColumn[]
  items: FamilyAttendanceFormModelItem[]
}
