import { BaseTable } from '@/types'

export interface StudentTable extends BaseTable {
  surname: string
  name: string
  patronymic: string | null
  sex: 'мужской' | 'женский'
  birthday: string | null
  citizenship: string | null
  home_phone: string | null
  phone: string | null
  educational_institution: string | null
  social_conditions: string | null
  hobbies: string | null
  other_details: string | null
  medical_certificate_date: string | null
  health: string | null
  apprenticeship: string | null
  image_url: string | null
  address_id: number | null
  study_address_id: number | null
  passport_id: number | null
  group_id: number
}
