<template>
  <!-- Blog list -->
  <div v-if="blogs.length" class="min-h-screen bg-white py-16 px-6">
    <div class="container mx-auto max-w-4xl">

      <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-2">Blog</h1>
      <p class="text-gray-400 text-sm mb-12">Thoughts, ideas, and things I've learned.</p>

      <div class="border-t border-gray-100 divide-y divide-gray-100">
        <nuxt-link v-for="blog in blogs" :key="blog.id" :to="`/blog/${blog.slug}`"
          class="group flex flex-col md:flex-row md:items-center gap-4 py-8 hover:bg-gray-50 -mx-4 px-4 rounded-xl transition-colors">
          <!-- Thumbnail -->
          <div class="w-full md:w-48 h-32 rounded-lg overflow-hidden bg-gray-100 shrink-0">
            <img :src="getCoverImage(blog)" :alt="blog.title"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              @error="e => e.target.src = 'https://blocks.astratic.com/img/general-img-landscape.png'" />
          </div>

          <!-- Content -->
          <div class="flex flex-col gap-2 flex-1">
            <div class="flex items-center gap-2">
              <span
                class="text-[10px] font-bold uppercase tracking-widest text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full">
                {{ blog.category }}
              </span>
              <span class="text-[10px] text-gray-300 font-medium">{{
                blog.published_at
                  ? new Date(blog.published_at).toLocaleDateString()
                  : 'Draft'
              }}</span>
            </div>
            <h2 class="text-lg font-bold text-gray-900 group-hover:underline underline-offset-2 leading-snug">
              {{ blog.title }}
            </h2>
            <p class="text-sm text-gray-500 leading-relaxed line-clamp-2">{{ blog.excerpt }}</p>
            <span
              class="text-xs font-bold text-black mt-1 inline-flex items-center gap-1 group-hover:gap-2 transition-all">
              Read more →
            </span>
          </div>
        </nuxt-link>
      </div>

    </div>
  </div>

  <!-- Empty state meme -->
  <div v-else class="relative min-h-screen flex items-center justify-center overflow-hidden px-4">

    <div class="absolute inset-0 overflow-hidden pointer-events-none select-none">
      <span class="absolute top-6 left-4 text-5xl font-black text-yellow-400 rotate-[-15deg] opacity-40">404</span>
      <span class="absolute top-10 right-8 text-3xl font-black text-black/10 rotate-[10deg]">NOPE</span>
      <span class="absolute bottom-20 left-10 text-4xl font-black text-black/10 rotate-[-8deg]">BRUH</span>
      <span class="absolute bottom-10 right-12 text-3xl font-black text-yellow-400 rotate-[12deg] opacity-40">😭</span>
      <span class="absolute top-1/3 left-6 text-2xl font-black text-black/10 rotate-[-20deg]">WHY</span>
      <span class="absolute top-1/4 right-16 text-6xl font-black text-black/10 rotate-[5deg]">?!</span>
      <span class="absolute bottom-1/3 right-4 text-3xl font-black text-black/10 rotate-[-12deg]">LOL</span>
    </div>

    <div class="relative z-10 bg-white border-4 border-black shadow-[8px_8px_0px_0px_#000] max-w-md w-full">
      <div class="bg-black px-4 py-3 text-center">
        <p class="text-white font-black text-2xl uppercase tracking-widest"
          style="font-family: Impact, fantasy; text-shadow: 2px 2px 0 #ef4444;">
          WHEN YOU VISIT THE BLOG PAGE
        </p>
      </div>
      <div class="relative border-b-4 border-black">
        <img src="https://c.tenor.com/B3H88xsT2BsAAAAd/tenor.gif" alt="Nope meme" class="w-full object-cover" />
        <p class="absolute bottom-3 left-0 right-0 text-center font-black text-white text-3xl uppercase"
          style="font-family: Impact, fantasy; text-shadow: 3px 3px 0 #000, -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000;">
          BUT THERE'S NOTHING HERE
        </p>
      </div>
      <div class="bg-black px-4 py-3 text-center">
        <p class="text-white font-black text-2xl uppercase tracking-widest"
          style="font-family: Impact, fantasy; text-shadow: 2px 2px 0 #ef4444;">
          អត់មាន BLOGS ទេ 💀
        </p>
      </div>
      <div class="bg-yellow-300 border-t-4 border-black p-6 flex flex-col items-center gap-3">
        <p class="font-black text-black text-sm uppercase tracking-wider text-center">
          😤 Skill issue. Come back later.
        </p>
        <nuxt-link to="/"
          class="bg-black text-yellow-300 font-black uppercase px-8 py-3 text-sm tracking-widest border-2 border-black shadow-[4px_4px_0px_0px_#ef4444] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all"
          style="font-family: Impact, fantasy;">
          ← TAKE ME HOME
        </nuxt-link>
        <p class="text-black/40 text-xs font-bold uppercase tracking-widest">(before i cry)</p>
      </div>
    </div>

  </div>
</template>

<script setup>
const config = useRuntimeConfig()

const { data, pending } = await useFetch(
  `${config.public.apiBase}/api/blogs`,
  { default: () => ({ data: [] }) }
)

const blogs = computed(() => data.value?.data || [])

const getCoverImage = (blog) => {
  if (blog.cover_image) {
    return `${config.public.apiBase}/storage/${blog.cover_image}`
  }
  return 'https://blocks.astratic.com/img/general-img-landscape.png'
}
</script>