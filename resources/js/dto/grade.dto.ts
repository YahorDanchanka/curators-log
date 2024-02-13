import { GradeTableGrade } from '@/types'

export class GradeDTO implements GradeTableGrade {
  public _i: GradeTableGrade['_i'] = 'GradeTableGrade'

  constructor(
    public type: GradeTableGrade['type'],
    public value: GradeTableGrade['value'],
    public elem?: GradeTableGrade['elem']
  ) {}

  getColorByType(type?: string): string {
    type = type ?? this.type
    return type === 'default' ? 'grey-4' : type === 'primary' ? 'deep-orange-4' : 'yellow-4'
  }

  getClasses(type?: GradeTableGrade['type']): object {
    type = type ?? this.type

    return {
      [`bg-${this.getColorByType(type)}`]: true,
    }
  }
}
