<template>
  <!-- Loading -->
  <div v-if="pending" class="min-h-screen bg-white flex items-center justify-center">
    <div class="flex flex-col items-center gap-3">
      <Icon name="mdi:loading" size="32" class="text-gray-300 animate-spin" />
      <p class="text-xs text-gray-400 uppercase tracking-widest font-bold">Loading...</p>
    </div>
  </div>

  <!-- Project list -->
  <div v-else-if="projects.length" class="min-h-screen bg-white py-16 px-6">
    <div class="container mx-auto max-w-4xl">

      <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-2">Projects</h1>
      <p class="text-gray-400 text-sm mb-12">Things I've built and shipped.</p>

      <div class="border-t border-gray-100 divide-y divide-gray-100">
        <nuxt-link
          v-for="item in projects"
          :key="item.id"
          :to="`/projects/${item.slug}`"
          class="group flex flex-col md:flex-row md:items-center gap-4 py-8 hover:bg-gray-50 -mx-4 px-4 rounded-xl transition-colors"
        >
          <!-- Thumbnail -->
          <div class="w-full md:w-48 h-32 rounded-lg overflow-hidden bg-gray-100 shrink-0">
            <img
              :src="getCoverImage(item)"
              :alt="item.title"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              @error="e => e.target.src = 'https://blocks.astratic.com/img/general-img-landscape.png'"
            />
          </div>

          <!-- Content -->
          <div class="flex flex-col gap-2 flex-1">
            <div class="flex items-center gap-2">
              <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full">
                {{ item.category }}
              </span>
              <span class="text-[10px] text-gray-300 font-medium">{{ item.date }}</span>
            </div>
            <h2 class="text-lg font-bold text-gray-900 group-hover:underline underline-offset-2 leading-snug">
              {{ item.title }}
            </h2>
            <p class="text-sm text-gray-500 leading-relaxed line-clamp-2">
              {{ item.short_description || item.description }}
            </p>
            <div v-if="item.technologies?.length" class="flex flex-wrap gap-1.5 mt-1">
              <span
                v-for="tech in item.technologies.slice(0, 4)"
                :key="tech"
                class="text-[10px] font-medium text-gray-400 bg-gray-50 border border-gray-100 px-2 py-0.5 rounded-full"
              >
                {{ tech }}
              </span>
              <span v-if="item.technologies.length > 4" class="text-[10px] text-gray-300 px-1">
                +{{ item.technologies.length - 4 }}
              </span>
            </div>
            <span class="text-xs font-bold text-black mt-1 inline-flex items-center gap-1 group-hover:gap-2 transition-all">
              View Project →
            </span>
          </div>
        </nuxt-link>
      </div>

    </div>
  </div>

  <!-- Empty state -->
  <div v-else class="relative min-h-screen bg-blue-400 flex items-center justify-center overflow-hidden px-4">
    <div class="absolute inset-0 overflow-hidden pointer-events-none select-none">
      <span class="absolute top-6 left-4 text-5xl font-black text-blue-500 rotate-[-15deg] opacity-40">WIP</span>
      <span class="absolute top-10 right-8 text-3xl font-black text-black/10 rotate-[10deg]">SOON™</span>
      <span class="absolute bottom-20 left-10 text-4xl font-black text-black/10 rotate-[-8deg]">CODING...</span>
      <span class="absolute bottom-10 right-12 text-3xl font-black text-blue-500 rotate-[12deg] opacity-40">🚧</span>
      <span class="absolute top-1/3 left-6 text-2xl font-black text-black/10 rotate-[-20deg]">GIT PUSH</span>
      <span class="absolute top-1/4 right-16 text-6xl font-black text-black/10 rotate-[5deg]">{ }</span>
      <span class="absolute bottom-1/3 right-4 text-3xl font-black text-black/10 rotate-[-12deg]">npm i</span>
      <span class="absolute top-1/2 left-1/4 text-2xl font-black text-black/10 rotate-[8deg]">undefined</span>
      <span class="absolute bottom-1/4 left-1/3 text-xl font-black text-black/10 rotate-[-5deg]">404</span>
    </div>

    <div class="relative z-10 bg-white border-4 border-black shadow-[8px_8px_0px_0px_#000] max-w-md w-full">
      <div class="bg-black px-4 py-3 text-center">
        <p class="text-white font-black text-2xl uppercase tracking-widest"
          style="font-family: Impact, fantasy; text-shadow: 2px 2px 0 #3b82f6;">
          WHEN SOMEONE ASKS FOR MY PROJECTS
        </p>
      </div>
      <div class="relative border-b-4 border-black">
        <img src="https://c.tenor.com/YQ-r_mFzlm0AAAAd/tenor.gif" alt="Working on it meme" class="w-full object-cover"
          @error="onImgError" ref="memeImg" />
        <p class="absolute bottom-3 left-0 right-0 text-center font-black text-white text-3xl uppercase"
          style="font-family: Impact, fantasy; text-shadow: 3px 3px 0 #000, -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000;">
          STILL COOKING 👨‍🍳
        </p>
      </div>
      <div class="bg-black px-4 py-3 text-center">
        <p class="text-white font-black text-2xl uppercase tracking-widest"
          style="font-family: Impact, fantasy; text-shadow: 2px 2px 0 #3b82f6;">
          អត់មាន PROJECTS ទេ 💀
        </p>
      </div>
      <div class="bg-blue-400 border-t-4 border-black p-6 flex flex-col items-center gap-3">
        <p class="font-black text-black text-sm uppercase tracking-wider text-center">
          🛠️ Currently breaking things in dev...
        </p>
        <div class="w-full border-2 border-black bg-white h-6 overflow-hidden">
          <div class="h-full animate-[progress_3s_ease-in-out_infinite]"
            style="background: repeating-linear-gradient(45deg, #000 0px, #000 10px, #facc15 10px, #facc15 20px);">
          </div>
        </div>
        <p class="text-black font-black text-xs uppercase tracking-widest">Loading projects... (it's a lie)</p>
        <nuxt-link to="/"
          class="bg-black text-blue-400 font-black uppercase px-8 py-3 text-sm tracking-widest border-2 border-black shadow-[4px_4px_0px_0px_#3b82f6] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all mt-1"
          style="font-family: Impact, fantasy;">
          ← GO BACK HOME
        </nuxt-link>
        <p class="text-black/40 text-xs font-bold uppercase tracking-widest">(check github instead lol)</p>
      </div>
    </div>
  </div>
</template>

<script setup>
const config = useRuntimeConfig()
const memeImg = ref(null)

const { data, pending } = await useFetch(
  `${config.public.apiBase}/api/projects`,
  { default: () => ({ data: [] }) }
)

const projects = computed(() => data.value?.data || data.value || [])

const getCoverImage = (item) => {
  if (item?.image) {
    return `${config.public.apiBase}/storage/${item.image}`
  }

  return 'https://blocks.astratic.com/img/general-img-landscape.png'
}

function onImgError() {
  if (memeImg.value) {
    memeImg.value.src = 'https://media.tenor.com/DdpSGDqGTGwAAAAd/cat-typing.gif'
  }
}
</script>

<style>
@keyframes progress {
  0%   { width: 0% }
  50%  { width: 75% }
  100% { width: 0% }
}
</style>