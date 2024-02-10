import { inertiaFetch } from '@/helpers'
import { CuratorFormModel } from '@/types'

export class CuratorService {
  static create(payload: CuratorFormModel) {
    return inertiaFetch(route('curators.store'), {
      data: { ...payload },
      method: 'post',
    })
  }

  static update(curatorId: string | number, payload: CuratorFormModel) {
    return inertiaFetch(route('curators.update', { curator: curatorId }), {
      data: { ...payload, _method: 'put' },
      method: 'post',
    })
  }

  static delete(curatorId: string | number) {
    return inertiaFetch(route('curators.destroy', { curator: curatorId }), {
      method: 'delete',
    })
  }
}
