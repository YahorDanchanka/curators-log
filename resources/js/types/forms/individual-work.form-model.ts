import { IndividualWorkTable } from '@/types'

export type IndividualWorkFormModel = Pick<IndividualWorkTable, 'date' | 'content' | 'result' | 'type'>
