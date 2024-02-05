import { inertiaFetch } from '@/helpers'
import { StudentFormModel } from '@/types'

export class GroupStudentService {
  static create(groupId: string | number, payload: StudentFormModel) {
    return inertiaFetch(route('groups.students.store', { group: groupId }), {
      data: { ...payload },
      method: 'post',
    })
  }

  static update(groupId: string | number, studentId: string | number, payload: StudentFormModel) {
    return inertiaFetch(route('groups.students.update', { group: groupId, student: studentId }), {
      data: { ...payload, _method: 'put' },
      method: 'post',
    })
  }

  static delete(groupId: string | number, studentId: string | number) {
    return inertiaFetch(route('groups.students.destroy', { group: groupId, student: studentId }), {
      method: 'delete',
    })
  }
}
