import { GradeTableSubject, StudentModel } from '@/types'

export class GradeSubjectDTO {
  private _i: GradeTableSubject['_i'] = 'GradeTableSubject'

  constructor(public name: GradeTableSubject['name'], public rows: GradeTableSubject['rows'] = {}) {}

  getGrade(student_id: StudentModel['id']): number {
    if (student_id in this.rows) {
      return this.rows[student_id].getAvgGrade()
    }

    return NaN
  }
}
