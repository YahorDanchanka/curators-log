import { inertiaFetch } from '@/helpers'
import { ExpertAdviceFormModel } from '@/types'

export class ExpertAdviceService {
  static create(groupId: string | number, studentNumber: string | number, payload: ExpertAdviceFormModel) {
    return inertiaFetch(route('groups.students.expert-advice.store', { group: groupId, student: studentNumber }), {
      data: { ...payload },
      method: 'post',
    })
  }

  static update(
    groupId: string | number,
    studentNumber: string | number,
    adviceId: string | number,
    payload: ExpertAdviceFormModel
  ) {
    return inertiaFetch(
      route('groups.students.expert-advice.update', {
        group: groupId,
        student: studentNumber,
        expert_advice: adviceId,
      }),
      {
        data: { ...payload, _method: 'put' },
        method: 'post',
      }
    )
  }

  static delete(groupId: string | number, studentNumber: string | number, adviceId: string | number) {
    return inertiaFetch(
      route('groups.students.expert-advice.destroy', {
        group: groupId,
        student: studentNumber,
        expert_advice: adviceId,
      }),
      {
        method: 'delete',
      }
    )
  }
}
