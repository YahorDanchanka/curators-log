import Joi from 'joi'
import i18n from '@/i18n'

export default Joi.defaults((schema) =>
  schema.options({
    abortEarly: false,
    messages: {
      'any.required': i18n.global.t('validation.required'),
      'string.empty': i18n.global.t('validation.required'),
      'date.base': i18n.global.t('validation.required'),
      'string.max': i18n.global.t('validation.max.string'),
      'number.base': i18n.global.t('validation.required'),
      'number.min': i18n.global.t('validation.min.numeric'),
      'number.max': i18n.global.t('validation.max.numeric'),
    },
  })
)
