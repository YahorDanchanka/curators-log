import { AddressFormModel, RelativeTable } from '@/types'

export type RelativeFormModel = Pick<
  RelativeTable,
  'surname' | 'name' | 'patronymic' | 'sex' | 'birthday' | 'job' | 'position' | 'phone' | 'educational_institution'
> & {
  address?: AddressFormModel | null
  type?: string | null
}
