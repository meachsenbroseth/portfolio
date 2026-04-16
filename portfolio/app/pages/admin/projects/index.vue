<template>
  <div>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <div>
        <h2 class="text-2xl font-black text-gray-900">Projects</h2>
        <p class="text-sm text-gray-400 mt-0.5">{{ projects.length }} projects</p>
      </div>
      <nuxt-link to="/admin/projects/create"
        class="inline-flex items-center gap-2 bg-black text-white text-xs font-bold uppercase tracking-widest px-4 py-2.5 rounded-full hover:bg-gray-800 transition-colors">
        <Icon name="mdi:plus" size="14" />
        New Project
      </nuxt-link>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
      <table class="w-full">
        <thead>
          <tr class="border-b border-gray-100">
            <th class="text-left text-[10px] font-black uppercase tracking-widest text-gray-400 px-6 py-4">Project</th>
            <th class="text-left text-[10px] font-black uppercase tracking-widest text-gray-400 px-6 py-4">Category</th>
            <th class="text-left text-[10px] font-black uppercase tracking-widest text-gray-400 px-6 py-4">Technologies
            </th>
            <th class="text-left text-[10px] font-black uppercase tracking-widest text-gray-400 px-6 py-4">Date</th>
            <th class="text-left text-[10px] font-black uppercase tracking-widest text-gray-400 px-6 py-4">Status</th>
            <th class="px-6 py-4"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="project in projects" :key="project.id" class="hover:bg-gray-50 transition-colors group">
            <!-- Project -->
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div>
                  <p class="text-sm font-semibold text-gray-900">{{ project.title }}</p>
                  <p class="text-xs text-gray-400 mt-0.5 line-clamp-1 max-w-48">{{ project.short_description }}</p>
                </div>
              </div>
            </td>

            <!-- Category -->
            <td class="px-6 py-4">
              <span
                class="text-[10px] font-bold uppercase tracking-widest text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full whitespace-nowrap">
                {{ project.category }}
              </span>
            </td>

            <!-- Technologies -->
            <td class="px-6 py-4">
              <div class="flex flex-wrap gap-1">
                <span v-for="tech in (project.technologies || []).slice(0, 3)" :key="tech"
                  class="text-[10px] font-medium text-gray-500 bg-gray-50 border border-gray-100 px-2 py-0.5 rounded-full">
                  {{ tech }}
                </span>
                <span v-if="(project.technologies || []).length > 3"
                  class="text-[10px] font-medium text-gray-400 px-2 py-0.5">
                  +{{ project.technologies.length - 3 }}
                </span>
              </div>
            </td>

            <!-- Date -->
            <td class="px-6 py-4 text-sm text-gray-400 whitespace-nowrap">{{ project.date }}</td>

            <!-- Status -->
            <td class="px-6 py-4">
              <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full"
                :class="project.status === 'published' ? 'bg-black text-white' : 'bg-gray-100 text-gray-500'">
                {{ project.status }}
              </span>
            </td>

            <!-- Actions -->
            <td class="px-6 py-4">
              <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity justify-end">
                <a v-if="project.github_link" :href="project.github_link" target="_blank"
                  class="p-1.5 hover:bg-gray-100 rounded-lg transition-colors" title="GitHub">
                  <Icon name="mdi:github" size="14" class="text-gray-500" />
                </a>
                <nuxt-link :to="`/admin/projects/create?id=${project.slug}`"
                  class="p-1.5 hover:bg-gray-100 rounded-lg transition-colors">
                  <Icon name="mdi:pencil" size="14" class="text-gray-500" />
                </nuxt-link>
                <button @click="deleteProject(project.id)" class="p-1.5 hover:bg-red-50 rounded-lg transition-colors">
                  <Icon name="mdi:trash-can" size="14" class="text-red-400" />
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Empty state -->
      <div v-if="projects.length === 0" class="flex flex-col items-center justify-center py-20 text-center">
        <Icon name="mdi:briefcase-outline" size="40" class="text-gray-200 mb-3" />
        <p class="text-sm font-semibold text-gray-400">No projects yet</p>
        <p class="text-xs text-gray-300 mt-1 mb-4">Create your first project to get started</p>
        <nuxt-link to="/admin/projects/create"
          class="inline-flex items-center gap-2 bg-black text-white text-xs font-bold uppercase tracking-widest px-4 py-2.5 rounded-full hover:bg-gray-800 transition-colors">
          <Icon name="mdi:plus" size="14" />
          New Project
        </nuxt-link>
      </div>
    </div>

  </div>
</template>

<script setup>
definePageMeta({ layout: 'admin-layout' })

const config = useRuntimeConfig()
const client = useSanctumClient()

const { data, pending, refresh } = await useFetch(
  `${config.public.apiBase}/api/projects`,
  { default: () => [] }
)

const projects = computed(() => data.value?.data || data.value || [])

async function deleteProject(id) {
  if (!confirm('Delete this project?')) return

  try {
    await client(`${config.public.apiBase}/api/projects/${id}`, {
      method: 'DELETE',
    })
    await refresh()
  } catch (error) {
    console.error('Failed to delete project:', error)
    alert('Failed to delete project. Please try again.')
  }
}
</script>