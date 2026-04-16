<template>
  <div class="p-6 md:p-8">
    <!-- Welcome Header -->
    <div class="mb-8">
      <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Welcome back, Admin 👋</h1>
      <p class="text-gray-500 mt-1">Here's what's happening with your portfolio today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Projects</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ stats.projects }}</p>
          </div>
          <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center">
            <Icon name="mdi:briefcase-outline" size="20" class="text-blue-500" />
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Blog Posts</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ stats.blogs }}</p>
          </div>
          <div class="h-10 w-10 rounded-full bg-emerald-50 flex items-center justify-center">
            <Icon name="mdi:post-outline" size="20" class="text-emerald-500" />
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Education</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ stats.education }}</p>
          </div>
          <div class="h-10 w-10 rounded-full bg-purple-50 flex items-center justify-center">
            <Icon name="mdi:school-outline" size="20" class="text-purple-500" />
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Experience</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ stats.experience }}</p>
          </div>
          <div class="h-10 w-10 rounded-full bg-amber-50 flex items-center justify-center">
            <Icon name="mdi:briefcase-clock-outline" size="20" class="text-amber-500" />
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Quick Actions -->
      <div class="lg:col-span-1 bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h2>
        <div class="space-y-3">
          <NuxtLink to="/admin/projects/create" 
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors group">
            <div class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-blue-100">
              <Icon name="mdi:plus" size="16" class="text-blue-500" />
            </div>
            <span class="text-sm font-medium text-gray-700">Add New Project</span>
          </NuxtLink>
          <NuxtLink to="/admin/blogs/create"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors group">
            <div class="h-8 w-8 rounded-full bg-emerald-50 flex items-center justify-center group-hover:bg-emerald-100">
              <Icon name="mdi:plus" size="16" class="text-emerald-500" />
            </div>
            <span class="text-sm font-medium text-gray-700">Write New Blog Post</span>
          </NuxtLink>
          <NuxtLink to="/admin/education/create"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors group">
            <div class="h-8 w-8 rounded-full bg-purple-50 flex items-center justify-center group-hover:bg-purple-100">
              <Icon name="mdi:plus" size="16" class="text-purple-500" />
            </div>
            <span class="text-sm font-medium text-gray-700">Add Education Entry</span>
          </NuxtLink>
          <NuxtLink to="/admin/experience/create"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors group">
            <div class="h-8 w-8 rounded-full bg-amber-50 flex items-center justify-center group-hover:bg-amber-100">
              <Icon name="mdi:plus" size="16" class="text-amber-500" />
            </div>
            <span class="text-sm font-medium text-gray-700">Add Work Experience</span>
          </NuxtLink>
        </div>
      </div>

      <!-- Recent Activity Placeholder -->
      <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Recent Activity</h2>
        <div class="space-y-4">
          <div v-if="recentActivity.length === 0" class="text-center py-8">
            <Icon name="mdi:inbox-outline" size="32" class="text-gray-300 mx-auto mb-2" />
            <p class="text-gray-400 text-sm">No recent activity yet</p>
          </div>
          <div v-for="activity in recentActivity" :key="activity.id" 
            class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
            <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center shrink-0">
              <Icon :name="activity.icon" size="14" :class="activity.iconColor" />
            </div>
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-900">{{ activity.message }}</p>
              <p class="text-xs text-gray-400 mt-0.5">{{ activity.time }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'admin-layout'
})

// Stats - these would normally come from an API
const stats = ref({
  projects: 0,
  blogs: 0,
  education: 0,
  experience: 0
})

// Recent activity placeholder
const recentActivity = ref([])

// Fetch real data from your API
const fetchDashboardData = async () => {
  try {
    const config = useRuntimeConfig()
    const apiBase = config.public.apiBase
    
    // Fetch counts from your endpoints (adjust endpoints as needed)
    const [projects, blogs, education, experience] = await Promise.all([
      $fetch(`${apiBase}/api/projects`, { method: 'GET' }).catch(() => ({ data: [] })),
      $fetch(`${apiBase}/api/blogs`, { method: 'GET' }).catch(() => ({ data: [] })),
      $fetch(`${apiBase}/api/education`, { method: 'GET' }).catch(() => ({ data: [] })),
      $fetch(`${apiBase}/api/experience`, { method: 'GET' }).catch(() => ({ data: [] }))
    ])
    
    stats.value = {
      projects: projects.data?.length || 0,
      blogs: blogs.data?.length || 0,
      education: education.data?.length || 0,
      experience: experience.data?.length || 0
    }
    
    // Example: populate recent activity (you can extend this with actual recent items)
    // This is just a placeholder – you would fetch from a 'recent' endpoint or combine latest entries
    const allRecent = [
      ...(projects.data?.slice(0, 2).map(p => ({ 
        id: p.id, type: 'project', message: `Added project: ${p.title}`, 
        icon: 'mdi:briefcase-outline', iconColor: 'text-blue-500', time: 'Just now' 
      })) || []),
      ...(blogs.data?.slice(0, 2).map(b => ({ 
        id: b.id, type: 'blog', message: `Published blog: ${b.title}`, 
        icon: 'mdi:post-outline', iconColor: 'text-emerald-500', time: 'Just now' 
      })) || [])
    ].slice(0, 5)
    
    recentActivity.value = allRecent
  } catch (error) {
    console.error('Failed to fetch dashboard data', error)
  }
}

// Call on mount
fetchDashboardData()
</script>

<style scoped>
/* Optional: subtle hover transitions */
</style>