import { router } from '@inertiajs/vue3'
import { GroupTable } from '@/types'

export class GroupService {
  static create(payload: Pick<GroupTable, 'number' | 'specialty_id'>) {
    router.post(route('groups.store'), { ...payload })
  }

  static update(groupId: string | number, payload: Pick<GroupTable, 'number' | 'specialty_id'>) {
    router.put(route('groups.update', { group: groupId }), { ...payload })
  }

  static delete(groupId: string | number) {
    router.delete(route('groups.destroy', { group: groupId }))
  }
}
