import { AddressModel, RelativeTable } from '@/types'

export type RelativeModel = RelativeTable & {
  full_name?: string
  initials?: string
  address?: AddressModel
  pivot?: {
    type: string
    student_id: number
    relative_id: number
  }
}
