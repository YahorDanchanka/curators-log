import { inertiaFetch } from '@/helpers'
import { AsocialBehaviorFormModel, StudentAchievementFormModel } from '@/types'

export class AsocialBehaviorService {
  static create(groupId: string | number, studentNumber: string | number, payload: AsocialBehaviorFormModel) {
    return inertiaFetch(route('groups.students.asocial-behaviors.store', { group: groupId, student: studentNumber }), {
      data: { ...payload },
      method: 'post',
    })
  }

  static update(
    groupId: string | number,
    studentNumber: string | number,
    asocialBehaviorId: string | number,
    payload: AsocialBehaviorFormModel
  ) {
    return inertiaFetch(
      route('groups.students.asocial-behaviors.update', {
        group: groupId,
        student: studentNumber,
        asocial_behavior: asocialBehaviorId,
      }),
      {
        data: { ...payload, _method: 'put' },
        method: 'post',
      }
    )
  }

  static delete(groupId: string | number, studentNumber: string | number, asocialBehaviorId: string | number) {
    return inertiaFetch(
      route('groups.students.asocial-behaviors.destroy', {
        group: groupId,
        student: studentNumber,
        asocial_behavior: asocialBehaviorId,
      }),
      {
        method: 'delete',
      }
    )
  }
}
