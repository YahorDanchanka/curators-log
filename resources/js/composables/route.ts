import { router } from '@inertiajs/vue3'
import { computed, onMounted, onUnmounted, ref } from 'vue'
import route from 'ziggy-js'

export function useRoute() {
  const current = ref(route().current())

  const url = computed<string | null>(() => {
    try {
      return route(current.value)
    } catch (error) {}

    return null
  })

  let navigateEventListener: VoidFunction | null = null

  onMounted(() => {
    navigateEventListener = router.on('navigate', () => {
      current.value = route().current()
    })
  })

  onUnmounted(() => navigateEventListener && navigateEventListener())

  return { current, url }
}
