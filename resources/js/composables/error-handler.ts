import { ComputedRef, watch } from 'vue'
import { useQuasar } from 'quasar'
import type { IErrors } from '@/types'

export function useErrorHandler(errors: ComputedRef<IErrors>) {
  const $q = useQuasar()

  watch(
    errors,
    () => {
      for (const key of Object.keys(errors.value)) {
        $q.notify({ type: 'negative', message: errors.value[key] })
      }
    },
    { deep: true }
  )
}
