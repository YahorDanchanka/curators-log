import { IEnum } from '@/types'

export class EnumRepository {
  constructor(protected enumerable: IEnum) {}

  get(name: string) {
    return this.enumerable.find((item) => item.name === name)?.value
  }
}
