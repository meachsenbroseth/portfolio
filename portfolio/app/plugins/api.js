// plugins/api.js
export default defineNuxtPlugin(() => {
  const api = $fetch.create({
    baseURL: useRuntimeConfig().public.apiBase,
    credentials: 'include',
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  return { provide: { api } }
})