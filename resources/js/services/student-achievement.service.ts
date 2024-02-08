import { inertiaFetch } from '@/helpers'
import { StudentAchievementFormModel } from '@/types'

export class StudentAchievementService {
  static create(groupId: string | number, studentNumber: string | number, payload: StudentAchievementFormModel) {
    return inertiaFetch(route('groups.students.achievements.store', { group: groupId, student: studentNumber }), {
      data: { ...payload },
      method: 'post',
    })
  }

  static update(
    groupId: string | number,
    studentNumber: string | number,
    achievementId: string | number,
    payload: StudentAchievementFormModel
  ) {
    return inertiaFetch(
      route('groups.students.achievements.update', {
        group: groupId,
        student: studentNumber,
        achievement: achievementId,
      }),
      {
        data: { ...payload, _method: 'put' },
        method: 'post',
      }
    )
  }

  static delete(groupId: string | number, studentNumber: string | number, achievementId: string | number) {
    return inertiaFetch(
      route('groups.students.achievements.destroy', {
        group: groupId,
        student: studentNumber,
        achievement: achievementId,
      }),
      {
        method: 'delete',
      }
    )
  }
}
