<template>
  <div class="min-h-screen bg-white py-16 px-6">
    <div class="container mx-auto max-w-2xl">

      <!-- Back -->
      <nuxt-link to="/blog"
        class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-black transition-colors mb-10 group">
        <span class="group-hover:-translate-x-1 transition-transform inline-block">←</span>
        Back to Blog
      </nuxt-link>

      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center gap-2 mb-4">
          <span
            class="text-[10px] font-bold uppercase tracking-widest text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full">
            {{ blog.category }}
          </span>
          <span class="text-xs text-gray-300">{{ blog.published_at ? new Date(blog.published_at).toLocaleDateString() :
            'Draft' }}</span>
        </div>
        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 leading-tight mb-4">
          {{ blog.title }}
        </h1>
        <p class="text-gray-500 text-base leading-relaxed">{{ blog.excerpt }}</p>
      </div>

      <!-- Cover Image -->
      <div class="w-full aspect-video rounded-xl overflow-hidden border border-gray-200 shadow-md mb-12">
        <img :src="coverImage" :alt="blog.title" class="w-full h-full object-cover"
          @error="e => e.target.src = 'https://blocks.astratic.com/img/general-img-landscape.png'" />
      </div>

      <!-- Content -->
      <article class="prose prose-gray max-w-none mb-16">
        <p class="whitespace-pre-line">
          {{ blog.content }}
        </p>
      </article>

    </div>
  </div>
</template>

<script setup>
const route = useRoute()
const config = useRuntimeConfig()

const { data, pending, error } = await useFetch(
  `${config.public.apiBase}/api/blogs/${route.params.slug}`
)

const coverImage = computed(() => {
  if (blog.value.cover_image) {
    return `${config.public.apiBase}/storage/${blog.value.cover_image}`
  }

  return 'https://blocks.astratic.com/img/general-img-landscape.png'
})

const blog = computed(() => data.value?.data || {})
</script>