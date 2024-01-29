import { CharacteristicStudentTable, StudentModel } from '@/types'

export class StudentRepository {
  protected students: StudentModel[] = []

  constructor(students: StudentModel[] = []) {
    this.students = JSON.parse(JSON.stringify(students))
  }

  getAttachedCharacteristics() {
    return this.students.map((student) => student.characteristics || []).flat()
  }

  getPivotAttachedCharacteristics(): CharacteristicStudentTable[] {
    return this.getAttachedCharacteristics().map((characteristic) => characteristic.pivot)
  }

  getBRSMStudents() {
    return this.students.filter((student) =>
      student.characteristics?.some((characteristic) => characteristic.id === 30)
    )
  }

  getLeadershipStudents() {
    return this.students.filter((student) =>
      student.characteristics?.some((characteristic) =>
        [19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29].includes(characteristic.id)
      )
    )
  }
}
