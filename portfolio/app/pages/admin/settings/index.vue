<template>
  <div class="max-w-3xl">

    <!-- Header -->
    <div class="mb-8">
      <h2 class="text-2xl font-black text-gray-900">Settings</h2>
      <p class="text-sm text-gray-400 mt-0.5">Manage your account and preferences</p>
    </div>

    <div class="flex flex-col gap-6">

      <!-- Profile -->
      <div class="bg-white rounded-2xl border border-gray-100 p-6 flex flex-col gap-5">
        <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Profile</h3>

        <!-- Success / Error -->
        <div v-if="profileSuccess" class="bg-green-50 border border-green-100 rounded-lg px-4 py-3 flex items-center gap-2">
          <Icon name="mdi:check-circle-outline" size="16" class="text-green-500 shrink-0" />
          <p class="text-xs font-medium text-green-600">Profile updated successfully.</p>
        </div>
        <div v-if="profileError" class="bg-red-50 border border-red-100 rounded-lg px-4 py-3 flex items-center gap-2">
          <Icon name="mdi:alert-circle-outline" size="16" class="text-red-400 shrink-0" />
          <p class="text-xs font-medium text-red-500">{{ profileError }}</p>
        </div>

        <div class="flex flex-col gap-4">
          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Name</label>
            <input
              v-model="profileForm.name"
              type="text"
              placeholder="Your name"
              class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-black transition-colors"
            />
          </div>
          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Email</label>
            <input
              v-model="profileForm.email"
              type="email"
              placeholder="your@email.com"
              class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-black transition-colors"
            />
          </div>
        </div>

        <div class="flex justify-end">
          <button
            @click="updateProfile"
            :disabled="profileLoading"
            class="inline-flex items-center gap-2 bg-black text-white text-xs font-black uppercase tracking-widest px-6 py-2.5 rounded-full hover:bg-gray-800 transition-colors disabled:opacity-50"
          >
            <Icon v-if="profileLoading" name="mdi:loading" size="14" class="animate-spin" />
            Save Profile
          </button>
        </div>
      </div>

      <!-- Change Password -->
      <div class="bg-white rounded-2xl border border-gray-100 p-6 flex flex-col gap-5">
        <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Change Password</h3>

        <div v-if="passwordSuccess" class="bg-green-50 border border-green-100 rounded-lg px-4 py-3 flex items-center gap-2">
          <Icon name="mdi:check-circle-outline" size="16" class="text-green-500 shrink-0" />
          <p class="text-xs font-medium text-green-600">Password changed successfully.</p>
        </div>
        <div v-if="passwordError" class="bg-red-50 border border-red-100 rounded-lg px-4 py-3 flex items-center gap-2">
          <Icon name="mdi:alert-circle-outline" size="16" class="text-red-400 shrink-0" />
          <p class="text-xs font-medium text-red-500">{{ passwordError }}</p>
        </div>

        <div class="flex flex-col gap-4">
          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Current Password</label>
            <div class="relative">
              <input
                v-model="passwordForm.current_password"
                :type="show.current ? 'text' : 'password'"
                placeholder="••••••••"
                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-black transition-colors pr-12"
              />
              <button type="button" @click="show.current = !show.current"
                class="absolute right-4 top-0 h-full flex items-center text-gray-400 hover:text-black transition-colors">
                <Icon :name="show.current ? 'mdi:eye-off-outline' : 'mdi:eye-outline'" size="16" />
              </button>
            </div>
            <p v-if="passwordErrors.current_password" class="text-xs text-red-500">
              {{ passwordErrors.current_password[0] }}
            </p>
          </div>

          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">New Password</label>
            <div class="relative">
              <input
                v-model="passwordForm.password"
                :type="show.password ? 'text' : 'password'"
                placeholder="••••••••"
                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-black transition-colors pr-12"
              />
              <button type="button" @click="show.password = !show.password"
                class="absolute right-4 top-0 h-full flex items-center text-gray-400 hover:text-black transition-colors">
                <Icon :name="show.password ? 'mdi:eye-off-outline' : 'mdi:eye-outline'" size="16" />
              </button>
            </div>
            <p v-if="passwordErrors.password" class="text-xs text-red-500">
              {{ passwordErrors.password[0] }}
            </p>
          </div>

          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Confirm New Password</label>
            <div class="relative">
              <input
                v-model="passwordForm.password_confirmation"
                :type="show.confirm ? 'text' : 'password'"
                placeholder="••••••••"
                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-black transition-colors pr-12"
              />
              <button type="button" @click="show.confirm = !show.confirm"
                class="absolute right-4 top-0 h-full flex items-center text-gray-400 hover:text-black transition-colors">
                <Icon :name="show.confirm ? 'mdi:eye-off-outline' : 'mdi:eye-outline'" size="16" />
              </button>
            </div>
          </div>
        </div>

        <div class="flex justify-end">
          <button
            @click="changePassword"
            :disabled="passwordLoading"
            class="inline-flex items-center gap-2 bg-black text-white text-xs font-black uppercase tracking-widest px-6 py-2.5 rounded-full hover:bg-gray-800 transition-colors disabled:opacity-50"
          >
            <Icon v-if="passwordLoading" name="mdi:loading" size="14" class="animate-spin" />
            Update Password
          </button>
        </div>
      </div>

      <!-- Danger Zone -->
      <div class="bg-white rounded-2xl border border-red-100 p-6 flex flex-col gap-4">
        <h3 class="text-xs font-black uppercase tracking-widest text-red-400">Danger Zone</h3>
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-semibold text-gray-900">Logout from all devices</p>
            <p class="text-xs text-gray-400 mt-0.5">Invalidates all active sessions</p>
          </div>
          <button
            @click="logoutAll"
            class="text-xs font-bold text-red-500 border border-red-200 px-4 py-2 rounded-full hover:bg-red-50 transition-colors"
          >
            Logout All
          </button>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
definePageMeta({ layout: 'admin-layout' })

const config  = useRuntimeConfig()
const client  = useSanctumClient()
const user    = useSanctumUser()
const { logout } = useSanctumAuth()

// Profile
const profileForm    = ref({ name: user.value?.name ?? '', email: user.value?.email ?? '' })
const profileLoading = ref(false)
const profileSuccess = ref(false)
const profileError   = ref('')

async function updateProfile() {
  profileError.value   = ''
  profileSuccess.value = false
  profileLoading.value = true

  try {
    await client(`${config.public.apiBase}/api/profile`, {
      method: 'PUT',
      body:   profileForm.value,
    })
    profileSuccess.value = true
  } catch (e) {
    profileError.value = e?.data?.message ?? 'Failed to update profile.'
  }

  profileLoading.value = false
}

// Password
const passwordForm    = ref({ current_password: '', password: '', password_confirmation: '' })
const passwordLoading = ref(false)
const passwordSuccess = ref(false)
const passwordError   = ref('')
const passwordErrors  = ref({})

const show = ref({ current: false, password: false, confirm: false })

async function changePassword() {
  passwordError.value   = ''
  passwordSuccess.value = false
  passwordErrors.value  = {}

  if (!passwordForm.value.current_password || !passwordForm.value.password || !passwordForm.value.password_confirmation) {
    passwordError.value = 'Please fill in all fields.'
    return
  }

  if (passwordForm.value.password !== passwordForm.value.password_confirmation) {
    passwordError.value = 'New passwords do not match.'
    return
  }

  if (passwordForm.value.password.length < 8) {
    passwordError.value = 'New password must be at least 8 characters.'
    return
  }

  passwordLoading.value = true

  try {
    await client(`${config.public.apiBase}/api/resetpassword`, {
      method: 'POST',
      body:   passwordForm.value,
    })
    passwordSuccess.value = true
    passwordForm.value    = { current_password: '', password: '', password_confirmation: '' }
  } catch (e) {
    const data = e?.data
    if (data?.errors) {
      passwordErrors.value = data.errors
    } else {
      passwordError.value = data?.message ?? 'Something went wrong.'
    }
  }

  passwordLoading.value = false
}

// Logout all
async function logoutAll() {
  if (!confirm('This will log you out from all devices. Continue?')) return
  await logout()
}
</script>