import { LeadershipFormModel } from '@/types'
import { inertiaFetch } from '@/helpers'

export class LeadershipService {
  static sync(groupId: string | number, course_number: string | number, payload: LeadershipFormModel) {
    return inertiaFetch(route('groups.courses.leadership.sync', { group: groupId, course_number }), {
      method: 'post',
      data: payload,
    })
  }
}
