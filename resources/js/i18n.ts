import { createI18n } from 'vue-i18n'
import rui18n from '@/lang/ru'

export default createI18n({
  legacy: false,
  locale: 'ru',
  globalInjection: true,
  messages: {
    ru: rui18n,
  },
})
