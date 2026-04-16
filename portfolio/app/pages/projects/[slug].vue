<template>
  <div class="min-h-screen bg-white py-16">
    <div class="container mx-auto px-6 md:px-12">
      <div class="max-w-4xl mx-auto">

        <!-- Back Button -->
        <nuxt-link to="/projects"
          class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-black transition-colors mb-8 -ml-1 group">
          <span class="group-hover:-translate-x-1 transition-transform inline-block">←</span>
          Back to Projects
        </nuxt-link>

        <!-- Header -->
        <div class="mb-12">
          <span
            class="inline-block border border-gray-300 text-xs font-medium px-3 py-1 rounded-full mb-4 text-gray-500">
            {{ project.category }}
          </span>
          <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 text-gray-900">
            {{ project.title }}
          </h1>
          <div class="flex gap-4 text-gray-500 font-medium text-sm">
            <span>
              {{
                project.created_at
                  ? new Date(project.created_at).toLocaleDateString()
              : ''
              }}
            </span>
          </div>
        </div>

        <!-- Project Image -->
        <div class="aspect-video w-full overflow-hidden rounded-xl border border-gray-200 shadow-lg mb-12">
          <img :src="coverImage" :alt="project.title" class="w-full h-full object-cover"
            @error="e => e.target.src = fallbackImage" />
        </div>

        <!-- Overview -->
        <section class="mb-12">
          <h2 class="text-2xl font-bold mb-4 text-gray-900">Overview</h2>
          <p class="text-gray-600 text-lg leading-relaxed">{{ project.description }}</p>
        </section>

        <!-- Technologies -->
        <section class="mb-12">
          <h2 class="text-2xl font-bold mb-4 text-gray-900">Technologies Used</h2>
          <div class="flex flex-wrap gap-2">
            <span v-for="tech in project.technologies" :key="tech"
              class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-full">
              {{ tech }}
            </span>
          </div>
        </section>

        <!-- Actions -->
        <div class="flex flex-wrap gap-4 pt-8 border-t border-gray-200">
          <a :href="project.github_link"
            class="inline-flex items-center gap-2 border border-gray-300 text-gray-700 text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-gray-100 transition-colors shadow-sm">
            <Icon name="mdi:github" size="18" />
            View Code
          </a>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
const route = useRoute()
const config = useRuntimeConfig()

const fallbackImage =
  'https://blocks.astratic.com/img/general-img-landscape.png'

const coverImage = computed(() => {
  if (project.value.image) {
    return `${config.public.apiBase}/storage/${project.value.image}`
  }
  return fallbackImage
})

/* ===============================
   Fetch project by slug
=================================*/
const { data, pending, error } = await useFetch(
  `${config.public.apiBase}/api/projects/${route.params.slug}`
)

/* ===============================
   Laravel Resource wrapper support
   { data: {...} }
=================================*/
const project = computed(() => data.value?.data || {})
</script>