import { GradeDTO } from '@/dto'
import { meanBy } from 'lodash'

export class GradeRowDTO {
  constructor(public grades: GradeDTO[] = []) {}

  getAvgGrade(): number {
    return (
      (this.getAvgByType('default') + this.getAvgByType('primary') + this.getAvgByType('course')) /
      (+this.hasGrades('default') + +this.hasGrades('primary') + +this.hasGrades('course'))
    )
  }

  getFilteredGrades() {
    return this.grades.filter((grade) => grade.value === 0 || !!grade.value)
  }

  hasGrades(type: GradeDTO['type']): boolean {
    return this.getFilteredGrades().some((grade) => grade.type === type)
  }

  getGradesByType(type: GradeDTO['type']) {
    return this.getFilteredGrades().filter((grade) => grade.type === type)
  }

  getAvgByType(type: GradeDTO['type']) {
    return meanBy(this.getGradesByType(type), (grade) => grade.value) || 0
  }
}
