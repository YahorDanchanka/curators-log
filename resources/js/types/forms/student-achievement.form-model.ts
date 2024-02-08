import { StudentAchievementTable } from '@/types'

export type StudentAchievementFormModel = Pick<StudentAchievementTable, 'date' | 'reason' | 'prize'>
