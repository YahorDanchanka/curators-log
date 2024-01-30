import { BaseTable } from '@/types'

export interface RelativeTable extends BaseTable {
  surname: string
  name: string
  patronymic: string | null
  sex: 'мужской' | 'женский'
  birthday: string | null
  job: string | null
  position: string | null
  phone: string | null
  educational_institution: string | null
  address_id: number | null
}
