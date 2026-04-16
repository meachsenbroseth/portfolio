<template>
  <div>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <div>
        <h2 class="text-2xl font-black text-gray-900">Blogs</h2>
        <p class="text-sm text-gray-400 mt-0.5">{{ totalPosts }} posts</p>
      </div>
      <nuxt-link to="/admin/blogs/create"
        class="inline-flex items-center gap-2 bg-black text-white text-xs font-bold uppercase tracking-widest px-4 py-2.5 rounded-full hover:bg-gray-800 transition-colors">
        <Icon name="mdi:plus" size="14" />
        New Post
      </nuxt-link>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
      <table class="w-full">
        <thead>
          <tr class="border-b border-gray-100">
            <th class="text-left text-[10px] font-black uppercase tracking-widest text-gray-400 px-6 py-4">Post</th>
            <th class="text-left text-[10px] font-black uppercase tracking-widest text-gray-400 px-6 py-4">Category</th>
            <th class="text-left text-[10px] font-black uppercase tracking-widest text-gray-400 px-6 py-4">Tags</th>
            <th class="text-left text-[10px] font-black uppercase tracking-widest text-gray-400 px-6 py-4">Date</th>
            <th class="text-left text-[10px] font-black uppercase tracking-widest text-gray-400 px-6 py-4">Status</th>
            <th class="px-6 py-4"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="blog in blogs" :key="blog.id" class="hover:bg-gray-50 transition-colors group">
            <!-- Post -->
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div v-if="blog.cover_image" class="w-10 h-10 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                  <img :src="getImageUrl(blog.cover_image)" :alt="blog.title" class="w-full h-full object-cover" />
                </div>
                <div v-else class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                  <Icon name="mdi:file-document-outline" size="16" class="text-gray-400" />
                </div>
                <div>
                  <p class="text-sm font-semibold text-gray-900">{{ blog.title }}</p>
                  <p class="text-xs text-gray-400 mt-0.5 line-clamp-1 max-w-72">{{ blog.excerpt }}</p>
                </div>
              </div>
            </td>

            <!-- Category -->
            <td class="px-6 py-4">
              <span v-if="blog.category" 
                class="text-[10px] font-bold uppercase tracking-widest text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full whitespace-nowrap">
                {{ blog.category }}
              </span>
              <span v-else class="text-[10px] text-gray-300">-</span>
            </td>

            <!-- Tags -->
            <td class="px-6 py-4">
              <div class="flex flex-wrap gap-1">
                <span v-for="tag in (blog.tags || []).slice(0, 3)" :key="tag"
                  class="text-[10px] font-medium text-gray-500 bg-gray-50 border border-gray-100 px-2 py-0.5 rounded-full">
                  {{ tag }}
                </span>
                <span v-if="(blog.tags || []).length > 3"
                  class="text-[10px] font-medium text-gray-400 px-2 py-0.5">
                  +{{ blog.tags.length - 3 }}
                </span>
              </div>
            </td>

            <!-- Date -->
            <td class="px-6 py-4 text-sm text-gray-400 whitespace-nowrap">
              {{ formatDate(blog.published_at || blog.created_at) }}
            </td>

            <!-- Status -->
            <td class="px-6 py-4">
              <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full"
                :class="blog.status === 'published' ? 'bg-black text-white' : 'bg-gray-100 text-gray-500'">
                {{ blog.status }}
              </span>
            </td>

            <!-- Actions -->
            <td class="px-6 py-4">
              <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity justify-end">
                <button @click="previewPost(blog)" class="p-1.5 hover:bg-gray-100 rounded-lg transition-colors" title="Preview">
                  <Icon name="mdi:eye-outline" size="14" class="text-gray-500" />
                </button>
                <nuxt-link :to="`/admin/blogs/create?slug=${blog.slug}`" class="p-1.5 hover:bg-gray-100 rounded-lg transition-colors">
                  <Icon name="mdi:pencil" size="14" class="text-gray-500" />
                </nuxt-link>
                <button @click="deletePost(blog.id)" class="p-1.5 hover:bg-red-50 rounded-lg transition-colors">
                  <Icon name="mdi:trash-can" size="14" class="text-red-400" />
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Empty state -->
      <div v-if="blogs.length === 0" class="flex flex-col items-center justify-center py-20 text-center">
        <Icon name="mdi:file-document-outline" size="40" class="text-gray-200 mb-3" />
        <p class="text-sm font-semibold text-gray-400">No blog posts yet</p>
        <p class="text-xs text-gray-300 mt-1 mb-4">Create your first blog post to get started</p>
        <nuxt-link to="/admin/blogs/create"
          class="inline-flex items-center gap-2 bg-black text-white text-xs font-bold uppercase tracking-widest px-4 py-2.5 rounded-full hover:bg-gray-800 transition-colors">
          <Icon name="mdi:plus" size="14" />
          New Post
        </nuxt-link>
      </div>
    </div>

  </div>
</template>

<script setup>
definePageMeta({ layout: 'admin-layout' })

const config = useRuntimeConfig()
const client = useSanctumClient()

// Fetch blogs from API
const { data, pending, refresh } = await useFetch(
  `${config.public.apiBase}/api/blogs`,
  { default: () => [] }
)

// Process blogs data
const blogs = computed(() => {
  const blogData = data.value?.data || data.value || []
  
  // Parse tags if they're stored as JSON string
  return blogData.map(blog => ({
    ...blog,
    tags: parseTags(blog.tags)
  }))
})

// Calculate total posts count
const totalPosts = computed(() => blogs.value.length)

// Helper function to parse tags
function parseTags(tags) {
  if (!tags) return []
  if (Array.isArray(tags)) return tags
  if (typeof tags === 'string') {
    try {
      return JSON.parse(tags)
    } catch (e) {
      return tags.split(',').map(t => t.trim()).filter(t => t)
    }
  }
  return []
}

// Helper function to format date
function formatDate(dateString) {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long' })
}

// Helper function to get image URL
function getImageUrl(imagePath) {
  if (!imagePath) return ''
  if (imagePath.startsWith('http')) return imagePath
  return `${config.public.apiBase}/storage/${imagePath}`
}

// Delete post
async function deletePost(id) {
  if (!confirm('Are you sure you want to delete this blog post?')) return

  try {
    await client(`${config.public.apiBase}/api/blogs/${id}`, {
      method: 'DELETE',
    })
    await refresh()
  } catch (error) {
    console.error('Failed to delete post:', error)
    alert('Failed to delete post. Please try again.')
  }
}

// Preview post (open in new tab)
function previewPost(blog) {
  window.open(`/blog/${blog.slug}`, '_blank')
}
</script>
