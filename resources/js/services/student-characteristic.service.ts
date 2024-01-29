import { inertiaFetch } from '@/helpers'
import { BaseTable } from '@/types'

export class StudentCharacteristicService {
  static attach(studentId: BaseTable['id'], characteristicId: BaseTable['id'], courseId: BaseTable['id']) {
    return inertiaFetch(
      route('courses.students.characteristics.attach', {
        course: courseId,
        student: studentId,
        characteristic: characteristicId,
      }),
      { method: 'post' }
    )
  }

  static detach(studentId: BaseTable['id'], characteristicId: BaseTable['id'], courseId: BaseTable['id']) {
    return inertiaFetch(
      route('courses.students.characteristics.detach', {
        course: courseId,
        student: studentId,
        characteristic: characteristicId,
      }),
      { method: 'delete' }
    )
  }
}
