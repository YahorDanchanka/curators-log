import { BaseTable, StudentEmploymentTable } from '@/types'
import { StudentRepository } from '@/repositories'
import { inertiaFetch } from '@/helpers'

export type StudentEmploymentRow = Pick<StudentEmploymentTable, 'first_semester' | 'second_semester' | 'student_id'>

export class StudentEmploymentService {
  constructor(public data: StudentEmploymentRow[]) {}

  load(studentRepository: StudentRepository) {
    console.log(studentRepository.getLeadershipStudents())
    this.data = studentRepository.getLeadershipStudents().map((student) => {
      const employment = student.employments?.at(0)

      return {
        first_semester: employment?.first_semester,
        second_semester: employment?.second_semester,
        student_id: student.id,
      }
    })
  }

  findIndex(studentId: BaseTable['id']) {
    return this.data.findIndex((row) => row.student_id === studentId)
  }

  save(groupId: BaseTable['id'], courseNumber: string | number) {
    return inertiaFetch(
      route('groups.courses.student-employment.sync', {
        group: groupId,
        course_number: courseNumber,
      }),
      { data: { data: this.data }, method: 'post' }
    )
  }
}
