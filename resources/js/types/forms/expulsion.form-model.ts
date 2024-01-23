export interface ExpulsionFormModel {
  date: string
  initiator: 'student' | 'college'
  reason: string | null
  student_id: number | null
}
