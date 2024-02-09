import { inertiaFetch } from '@/helpers'
import { IndividualWorkFormModel } from '@/types'

export class IndividualWorkService {
  static create(groupId: string | number, studentNumber: string | number, payload: IndividualWorkFormModel) {
    return inertiaFetch(route('groups.students.individual-works.store', { group: groupId, student: studentNumber }), {
      data: { ...payload },
      method: 'post',
    })
  }

  static update(
    groupId: string | number,
    studentNumber: string | number,
    individualWorkId: string | number,
    payload: IndividualWorkFormModel
  ) {
    return inertiaFetch(
      route('groups.students.individual-works.update', {
        group: groupId,
        student: studentNumber,
        individual_work: individualWorkId,
      }),
      {
        data: { ...payload, _method: 'put' },
        method: 'post',
      }
    )
  }

  static delete(groupId: string | number, studentNumber: string | number, individualWorkId: string | number) {
    return inertiaFetch(
      route('groups.students.individual-works.destroy', {
        group: groupId,
        student: studentNumber,
        individual_work: individualWorkId,
      }),
      {
        method: 'delete',
      }
    )
  }
}
