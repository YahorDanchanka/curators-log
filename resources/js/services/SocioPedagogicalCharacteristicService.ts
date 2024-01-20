import { inertiaFetch } from '@/helpers'
import { CharacteristicStudentTable } from '@/types'

export class SocioPedagogicalCharacteristicService {
  static sync(groupId: string | number, courseId: string | number, characteristics: CharacteristicStudentTable[]) {
    return inertiaFetch(
      route('groups.courses.socio-pedagogical-characteristic.sync', { group: groupId, course: courseId }),
      {
        method: 'post',
        data: {
          data: characteristics,
        },
      }
    )
  }
}
