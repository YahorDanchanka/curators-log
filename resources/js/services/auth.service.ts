import { inertiaFetch } from '@/helpers'
import { AuthFormModel } from '@/types'

export class AuthService {
  static login(payload: AuthFormModel) {
    return inertiaFetch(route('auth.login'), {
      data: { ...payload },
      method: 'post',
    })
  }
}
