import { GradeDTO, GradeRowDTO, GradeSubjectDTO } from '@/dto'
import { StudentModel } from '@/types'
import { cloneDeep, mean } from 'lodash'

export class GradeTableDTO {
  constructor(public subjects: GradeSubjectDTO[] = []) {}

  static fromJSON(json: { subjects: { name: string; rows: { grades: { type: string; value: string }[] }[] }[] }) {
    const obj = new this()

    json.subjects.forEach((subject) => {
      const clonedRows = cloneDeep(subject.rows || {})

      for (const studentId in clonedRows) {
        const row = clonedRows[studentId]
        clonedRows[studentId] = new GradeRowDTO(row.grades.map((grade) => new GradeDTO(grade.type, grade.value)))
      }

      obj.subjects.push(new GradeSubjectDTO(subject.name, clonedRows))
    })

    return obj
  }

  addSubject(subject?: GradeSubjectDTO) {
    if (subject) {
      const foundSubject = this.findSubject(subject.name)

      if (foundSubject) {
        for (const studentId in foundSubject.rows) {
          if (!subject.rows[studentId]) continue
          foundSubject.rows[studentId].grades.push(...subject.rows[studentId].grades)
        }
      } else {
        this.subjects.push(subject)
      }

      return
    }

    this.subjects.push(new GradeSubjectDTO('Название предмета'))
  }

  findSubject(name: GradeSubjectDTO['name']) {
    return this.subjects.find((s) => s.name.toLowerCase().trim() === name.toLowerCase().trim())
  }

  getGrade(student: Pick<StudentModel, 'id'>) {
    return mean(
      this.subjects.map((subject) => subject.getGrade(student.id)).filter((item) => (item === 0 ? true : !!item))
    )
  }
}
