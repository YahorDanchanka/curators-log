import { GradeReportTable, GradeTable } from '@/types'

export type GradeReportModel = GradeReportTable & {
  grade?: GradeTable
}
