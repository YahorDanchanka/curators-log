import { router } from '@inertiajs/vue3'
import { ExpulsionFormModel } from '@/types'

export class ExpulsionService {
  static create(groupId: string | number, course_number: string | number, payload: ExpulsionFormModel) {
    router.post(route('groups.courses.expulsions.store', { group: groupId, course: course_number }), { ...payload })
  }

  static update(
    groupId: string | number,
    course_number: string | number,
    expulsionId: string | number,
    payload: ExpulsionFormModel
  ) {
    router.put(
      route('groups.courses.expulsions.update', { group: groupId, course: course_number, expulsion: expulsionId }),
      {
        ...payload,
      }
    )
  }

  static delete(groupId: string | number, course_number: string | number, expulsionId: string | number) {
    router.delete(
      route('groups.courses.expulsions.destroy', { group: groupId, course: course_number, expulsion: expulsionId })
    )
  }
}
