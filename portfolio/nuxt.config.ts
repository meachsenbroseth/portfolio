export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  ssr: false, // fixes sanctum SSR cookie issue

  modules: ['@nuxtjs/tailwindcss', '@nuxt/icon', 'nuxt-auth-sanctum'],

  sanctum: {
    baseUrl: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:80',
    mode: 'cookie',
    userStateKey: 'sanctum.user.identity',
    redirectIfAuthenticated: false,
    endpoints: {
      csrf:   '/sanctum/csrf-cookie',
      login:  '/api/login',
      logout: '/api/logout',
      user:   '/api/me',
    },
    csrf: {
      cookie: 'XSRF-TOKEN',
      header: 'X-XSRF-TOKEN',
    },
    redirect: {
      keepRequestedRoute: true,
      onLogin:            '/admin',
      onLogout:           '/admin/login',
      onAuthOnly:         '/admin/login',
      onGuestOnly:        '/admin',
    },
  },

  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:80',
    }
  },
})