<template>
  <aside class="fixed top-0 left-0 h-full w-56 bg-white border-r border-gray-100 flex flex-col z-10">
    <div class="p-6 border-b border-gray-100">
      <h1 class="font-black text-lg tracking-tight">Seth Admin</h1>
      <p class="text-xs text-gray-400 mt-0.5">Portfolio Dashboard</p>
    </div>

    <nav class="flex flex-col gap-1 p-4 flex-1">
      <nuxt-link v-for="tab in tabs" :key="tab.to" :to="tab.to"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors" :class="$route.path === tab.to
          ? 'bg-black text-white'
          : 'text-gray-500 hover:bg-gray-50 hover:text-black'">
        <Icon :name="tab.icon" size="16" />
        {{ tab.label }}
      </nuxt-link>
    </nav>
    <div class="p-4 border-t border-gray-100 flex flex-col gap-3">

      <!-- User info -->
      <div class="flex items-center gap-3 px-1">
        <div class="w-8 h-8 rounded-full bg-black flex items-center justify-center shrink-0">
          <span class="text-white text-xs font-black uppercase">
            {{ user?.name?.charAt(0) ?? 'A' }}
          </span>
        </div>
        <div class="min-w-0">
          <p class="text-sm font-semibold text-gray-900 truncate">{{ user?.name ?? 'Admin' }}</p>
          <p class="text-[10px] text-gray-400 truncate">{{ user?.email ?? '' }}</p>
        </div>
      </div>

      <div class="flex flex-col gap-1.5">
        <nuxt-link to="/" class="flex items-center gap-2 text-xs text-gray-400 hover:text-black transition-colors px-1">
          <Icon name="mdi:arrow-left" size="14" />
          Back to Site
        </nuxt-link>
        <button @click="handleLogout"
          class="flex items-center gap-2 text-xs text-red-400 hover:text-red-600 transition-colors px-1">
          <Icon name="mdi:logout" size="14" />
          Logout
        </button>
      </div>

    </div>
  </aside>
</template>

<script setup>
const { logout } = useSanctumAuth()
const user = useSanctumUser()

const tabs = [
  { to: '/admin', label: 'Overview', icon: 'mdi:view-dashboard' },
  { to: '/admin/blogs', label: 'Blogs', icon: 'mdi:post' },
  { to: '/admin/projects', label: 'Projects', icon: 'mdi:briefcase' },
  { to: '/admin/status', label: 'Update Status', icon: 'mdi:circle-edit-outline' },
  { to: '/admin/settings', label: 'Settings', icon: 'mdi:cog' },

]

async function handleLogout() {
  await logout()
}
</script>