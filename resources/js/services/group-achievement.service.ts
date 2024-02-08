import { inertiaFetch } from '@/helpers'
import { GroupAchievementFormModel } from '@/types'

export class GroupAchievementService {
  static create(groupId: string | number, courseNumber: string | number, payload: GroupAchievementFormModel) {
    return inertiaFetch(route('groups.courses.achievements.store', { group: groupId, course: courseNumber }), {
      data: { ...payload },
      method: 'post',
    })
  }

  static update(
    groupId: string | number,
    courseNumber: string | number,
    achievementId: string | number,
    payload: GroupAchievementFormModel
  ) {
    return inertiaFetch(
      route('groups.courses.achievements.update', { group: groupId, course: courseNumber, achievement: achievementId }),
      {
        data: { ...payload, _method: 'put' },
        method: 'post',
      }
    )
  }

  static delete(groupId: string | number, courseNumber: string | number, achievementId: string | number) {
    return inertiaFetch(
      route('groups.courses.achievements.destroy', {
        group: groupId,
        course: courseNumber,
        achievement: achievementId,
      }),
      {
        method: 'delete',
      }
    )
  }
}
