import { AdministrativeDivisionTable } from '@/types'

export class AdministrativeDivisionRepository {
  constructor(protected administrativeDivisions: AdministrativeDivisionTable[]) {}

  whereType(type: AdministrativeDivisionTable['type']) {
    return this.administrativeDivisions.filter((administrativeDivision) => administrativeDivision.type === type)
  }

  getRegions() {
    return this.mapToOptions(this.whereType('region'))
  }

  getDistricts(regionId: number) {
    return this.mapToOptions(
      this.whereType('district').filter((administrativeDivision) => administrativeDivision.parent_id === regionId)
    )
  }

  private mapToOptions(administrativeDivisions: AdministrativeDivisionTable[]) {
    return administrativeDivisions.map((administrativeDivision) => ({
      value: administrativeDivision.id,
      label: administrativeDivision.name,
    }))
  }
}
