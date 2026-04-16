// plugins/error-handler.js
export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.vueApp.config.errorHandler = (error) => {
    const status = error?.response?.status

    if (status === 403) {
      navigateTo('/')
    }
    if (status === 401) {
      navigateTo('/admin/login')
    }
  }
})