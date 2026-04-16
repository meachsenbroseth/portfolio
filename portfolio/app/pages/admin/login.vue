<template>
  <div class="w-full max-w-sm">

    <!-- Logo -->
    <div class="text-center mb-8">
      <h1 class="text-2xl font-black text-gray-900 tracking-tight">Seth Admin</h1>
      <p class="text-sm text-gray-400 mt-1">Sign in to your dashboard</p>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 flex flex-col gap-5">

      <!-- Error -->
      <div v-if="error" class="bg-red-50 border border-red-100 rounded-lg px-4 py-3 flex items-center gap-2">
        <Icon name="mdi:alert-circle-outline" size="16" class="text-red-400 shrink-0" />
        <p class="text-xs font-medium text-red-500">{{ error }}</p>
      </div>

      <!-- Email -->
      <div class="flex flex-col gap-1.5">
        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Email</label>
        <input v-model="form.email" type="email" placeholder="admin@seth.dev" @keydown.enter="login"
          class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-black transition-colors" />
      </div>

      <!-- Password -->
      <div class="relative">
        <input v-model="form.password" :type="showPassword ? 'text' : 'password'" placeholder="••••••••"
          @keydown.enter="login"
          class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-black transition-colors pr-12" />
        <button @click="showPassword = !showPassword" type="button"
          class="absolute right-4 top-0 h-full flex items-center text-gray-400 hover:text-black transition-colors">
          <Icon :name="showPassword ? 'mdi:eye-off-outline' : 'mdi:eye-outline'" size="16" />
        </button>
      </div>

      <!-- Submit -->
      <button @click="login" :disabled="loading"
        class="w-full bg-black text-white text-xs font-black uppercase tracking-widest py-3 rounded-full hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed mt-1">
        <span v-if="!loading">Sign In</span>
        <span v-else class="flex items-center justify-center gap-2">
          <Icon name="mdi:loading" size="14" class="animate-spin" />
          Signing in...
        </span>
      </button>

    </div>

    <!-- Back to site -->
    <div class="text-center mt-6">
      <nuxt-link to="/" class="text-xs text-gray-400 hover:text-black transition-colors">
        ← Back to site
      </nuxt-link>
    </div>

  </div>
</template>

<script setup>
definePageMeta({
  layout: 'auth-layout',
  middleware: 'sanctum:guest'
})

const { login: sanctumLogin } = useSanctumAuth();


const form = ref({ email: '', password: '' })
const error = ref('')
const loading = ref(false)
const showPassword = ref(false)

async function login() {
  error.value = ''

  if (!form.value.email || !form.value.password) {
    error.value = 'Please fill in all fields.'
    return
  }

  loading.value = true
  try {
    await sanctumLogin(form.value)
  } catch (e) {
    error.value = e?.response?.data?.message ?? 'Invalid email or password.'
    if (status === 401) {
      error.value = 'Invalid email or password.'
    } else if (status === 403) {
      error.value = 'You do not have admin access.'
    } else {
      error.value = 'Something went wrong. Please try again.'
    }
  }

  loading.value = false
}
</script>