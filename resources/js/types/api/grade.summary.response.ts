export interface GradeSummaryResponseItem {
  name: string
  rows: { [key: string | number]: number | false }
}

export type GradeSummaryResponse = GradeSummaryResponseItem[]
