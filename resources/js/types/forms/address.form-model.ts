import { AddressTable } from '@/types'

export type AddressFormModel = Pick<AddressTable, 'type' | 'residence' | 'street' | 'region_id' | 'district_id'>
