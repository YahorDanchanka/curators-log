import { AddressTable } from '@/types'

export type AddressFormModel = Pick<
  AddressTable,
  'type' | 'residence' | 'street' | 'apartment_number' | 'region_id' | 'district_id'
>
