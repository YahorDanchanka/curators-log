import { inertiaFetch } from '@/helpers'
import { InteractionWithParentFormModel } from '@/types'

export class InteractionWithParentService {
  static create(groupId: string | number, payload: InteractionWithParentFormModel) {
    return inertiaFetch(route('groups.interaction-with-parents.store', { group: groupId }), {
      data: { ...payload },
      method: 'post',
    })
  }

  static update(
    groupId: string | number,
    interactionWithParentId: string | number,
    payload: InteractionWithParentFormModel
  ) {
    return inertiaFetch(
      route('groups.interaction-with-parents.update', {
        group: groupId,
        interaction_with_parent: interactionWithParentId,
      }),
      {
        data: { ...payload, _method: 'put' },
        method: 'post',
      }
    )
  }

  static delete(groupId: string | number, interactionWithParentId: string | number) {
    return inertiaFetch(
      route('groups.interaction-with-parents.destroy', {
        group: groupId,
        interaction_with_parent: interactionWithParentId,
      }),
      {
        method: 'delete',
      }
    )
  }
}
