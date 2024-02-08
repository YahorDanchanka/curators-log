import { AdviceTable } from '@/types'

export type AdviceFormModel = Pick<AdviceTable, 'date' | 'comments' | 'suggestions' | 'full_name' | 'position'>
