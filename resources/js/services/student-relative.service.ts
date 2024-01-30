import { router } from '@inertiajs/vue3'
import { RelativeFormModel } from '@/types'

export class StudentRelativeService {
  static create(groupId: string | number, studentNumber: string | number, payload: RelativeFormModel) {
    router.post(route('groups.students.relatives.store', { group: groupId, student: studentNumber }), { ...payload })
  }

  static update(
    groupId: string | number,
    studentNumber: string | number,
    relativeId: string | number,
    payload: RelativeFormModel
  ) {
    router.put(
      route('groups.students.relatives.update', { group: groupId, student: studentNumber, relative: relativeId }),
      { ...payload }
    )
  }

  static delete(groupId: string | number, studentNumber: string | number, relativeId: string | number) {
    router.delete(
      route('groups.students.relatives.destroy', { group: groupId, student: studentNumber, relative: relativeId })
    )
  }
}
