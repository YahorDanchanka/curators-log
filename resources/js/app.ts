import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { Quasar, Notify, Dialog } from 'quasar'
import '@quasar/extras/material-icons/material-icons.css'
import 'quasar/src/css/index.sass'
import quasarLangRu from 'quasar/lang/ru'
import i18n from '@/i18n'
import MainLayout from '@/layouts/MainLayout.vue'
import '../css/app.sass'

createInertiaApp({
  resolve: (name) => {
    const pages = import.meta.glob('./pages/**/*.vue', { eager: true })
    const page: any = pages[`./pages/${name}.vue`]
    page.default.layout = page.default.layout || MainLayout
    return page
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(Quasar, { lang: quasarLangRu, plugins: { Notify, Dialog } })
      .use(i18n)
      .mount(el)
  },
})
