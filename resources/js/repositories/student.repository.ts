import { CharacteristicStudentTable, StudentModel } from '@/types'

export class StudentRepository {
  constructor(protected students: StudentModel[] = []) {}

  getAttachedCharacteristics() {
    return this.students.map((student) => student.characteristics || []).flat()
  }

  getPivotAttachedCharacteristics(): CharacteristicStudentTable[] {
    return this.getAttachedCharacteristics().map((characteristic) => characteristic.pivot)
  }
}
