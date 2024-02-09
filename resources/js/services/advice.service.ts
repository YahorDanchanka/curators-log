import { inertiaFetch } from '@/helpers'
import { AdviceFormModel } from '@/types'

export class AdviceService {
  static create(groupId: string | number, payload: AdviceFormModel) {
    return inertiaFetch(route('groups.advice.store', { group: groupId }), {
      data: { ...payload },
      method: 'post',
    })
  }

  static update(groupId: string | number, adviceId: string | number, payload: AdviceFormModel) {
    return inertiaFetch(route('groups.advice.update', { group: groupId, advice: adviceId }), {
      data: { ...payload, _method: 'put' },
      method: 'post',
    })
  }

  static delete(groupId: string | number, adviceId: string | number) {
    return inertiaFetch(route('groups.advice.destroy', { group: groupId, advice: adviceId }), {
      method: 'delete',
    })
  }
}
