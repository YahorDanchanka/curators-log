import { uid } from 'quasar'
import { groupBy } from 'lodash'
import { inertiaFetch } from '@/helpers'
import { BaseTable, PlanFormModel, PlanFormModelItem, PlanTable } from '@/types'

export class PlanService {
  constructor(public data: PlanFormModel) {}

  load(plans: PlanTable[]) {
    this.data = plans.map((planModel) => ({
      ...planModel,
      date: planModel.end_date ? { from: planModel.start_date, to: planModel.end_date } : planModel.start_date,
    }))
  }

  getGroupedPlansBySection(): { [key: string]: PlanFormModel } {
    return { DIAGNOSTIC: [], METHODIC: [], ORGANIZATIONAL_PEDAGOGICAL: [], ...groupBy(this.data, 'section') }
  }

  addPlan(section: PlanFormModelItem['section']) {
    this.data.push({
      id: uid(),
      date: '',
      content: '',
      done: 0,
      section: section,
    })
  }

  removePlan(id: PlanFormModelItem['id']) {
    this.data = this.data.filter((item) => item.id !== id)
  }

  save(groupId: BaseTable['id'], courseNumber: string | number, month: string | number) {
    return inertiaFetch(route('groups.courses.plans.sync', { group: groupId, course_number: courseNumber, month }), {
      data: {
        data: this.data.map((item) => ({
          ...item,
          start_date: typeof item.date === 'object' ? item.date.from : item.date,
          end_date: typeof item.date === 'object' ? item.date.to : null,
        })),
      },
      method: 'post',
    })
  }
}
