export default defineNuxtRouteMiddleware((to) => {
  const user = useSanctumUser()

  if (to.path.startsWith('/admin') && to.path !== '/admin/login' && !user.value) {
    return navigateTo('/admin/login')
  }

    if (to.path.startsWith('/admin') && to.path !== '/admin/login' && user.value?.role !== 'admin') {
    return navigateTo('/')
  }
})