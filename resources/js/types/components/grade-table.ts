import { GradeRowDTO } from '@/dto'

export interface GradeTableGrade {
  type: 'default' | 'primary' | 'course'
  value: number | string | false
  elem?: HTMLElement
  _i: 'GradeTableGrade'
}

export interface GradeTableSubject {
  name: string
  rows: { [studentId: string | number]: GradeRowDTO }
  elem?: HTMLElement
  _i: 'GradeTableSubject'
}
