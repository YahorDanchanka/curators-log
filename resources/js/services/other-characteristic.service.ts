import { inertiaFetch } from '@/helpers'
import { AttachedCharacteristic } from '@/types'

export class OtherCharacteristicService {
  static sync(groupId: string | number, courseNumber: string | number, characteristics: AttachedCharacteristic[]) {
    return inertiaFetch(
      route('groups.courses.other-characteristic.sync', { group: groupId, course_number: courseNumber }),
      {
        method: 'post',
        data: {
          data: characteristics,
        },
      }
    )
  }
}
