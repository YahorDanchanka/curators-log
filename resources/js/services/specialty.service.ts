import { inertiaFetch } from '@/helpers'
import { SpecialtyFormModel } from '@/types'

export class SpecialtyService {
  static create(payload: SpecialtyFormModel) {
    return inertiaFetch(route('specialties.store'), {
      data: { ...payload },
      method: 'post',
    })
  }

  static update(specialtyId: string | number, payload: SpecialtyFormModel) {
    return inertiaFetch(route('specialties.update', { specialty: specialtyId }), {
      data: { ...payload, _method: 'put' },
      method: 'post',
    })
  }

  static delete(specialtyId: string | number) {
    return inertiaFetch(route('specialties.destroy', { specialty: specialtyId }), {
      method: 'delete',
    })
  }
}
