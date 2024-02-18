import { AddressFormModel, PassportFormModel } from '@/types'

export interface StudentFormModel {
  surname: string
  name: string
  patronymic?: string | null
  sex: 'мужской' | 'женский'
  birthday?: string | null
  citizenship?: string | null
  home_phone?: string | null
  phone?: string | null
  educational_institution?: string | null
  social_conditions?: string | null
  hobbies?: string | null
  other_details?: string | null
  medical_certificate_date?: string | null
  health?: string | null
  apprenticeship?: string | null
  image?: File | null
  image_url?: string | null
  address?: AddressFormModel | null
  study_address?: AddressFormModel | null
  passport?: PassportFormModel | null
}
