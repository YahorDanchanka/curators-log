import { PlanTable } from '@/types'

export interface PlanFormModelItem {
  id: string | number
  date: { from: string; to: string } | string
  content: string
  done: 0 | 1
  section: PlanTable['section']
}

export type PlanFormModel = PlanFormModelItem[]
