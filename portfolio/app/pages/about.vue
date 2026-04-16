<template>
  <div class="bg-white min-h-screen">

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-white border-b border-gray-100 px-6 py-12 md:py-20">
      <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none overflow-hidden">
        <span class="text-[30vw] md:text-[20vw] font-black text-gray-100 tracking-tighter leading-none">SETH</span>
      </div>

      <div class="relative max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-16 items-center">

        <!-- Photo -->
        <div class="relative mx-auto w-full max-w-sm md:max-w-none">
          <div class="absolute -inset-3 border border-gray-200 rounded-3xl"></div>
          <img
            src="https://i.pinimg.com/736x/ad/83/26/ad83266f29567835516024bb1fdff3a7.jpg"
            alt="Seth"
            class="w-full aspect-[4/5] object-cover rounded-2xl relative z-10 transition-all duration-500"
          />
          <div class="absolute -bottom-4 -right-4 z-20 bg-black text-white text-[10px] md:text-xs font-bold uppercase tracking-widest px-3 md:px-4 py-2 rounded-full animate-pulse">
            ✦ Available for work
          </div>
        </div>

        <!-- Intro -->
        <div class="flex flex-col gap-5 md:gap-6 text-center md:text-left">
          <p class="text-xs font-semibold tracking-[0.25em] uppercase text-gray-400">— អំពីខ្ញុំ</p>
          <h1 class="text-5xl sm:text-6xl md:text-7xl font-black text-black leading-none tracking-tight">
            Hi,<br />
            I'm <span class="italic font-light border-b-4 border-black">Seth.</span>
          </h1>
          <p class="text-gray-500 text-sm leading-relaxed max-w-sm mx-auto md:mx-0">
            A passionate software engineer & web developer based in Cambodia.
            I craft clean, performant digital experiences — from backend logic to pixel-perfect UIs.
          </p>
        </div>
      </div>
    </section>

    <!-- Timeline Section -->
    <section class="max-w-5xl mx-auto px-6 py-12 md:py-20">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-16">

        <!-- Education -->
        <div>
          <div class="mb-8 md:mb-10">
            <span class="text-3xl">🎓</span>
            <h2 class="text-xl md:text-2xl font-black text-black mt-2 tracking-tight">ប្រវត្តិសិក្សា</h2>
            <p class="text-xs text-gray-400 uppercase tracking-[0.2em] font-semibold mt-1">Education</p>
          </div>

          <!-- Loading -->
          <div v-if="educationPending" class="border-l-2 border-gray-100 pl-6 flex flex-col gap-6 md:gap-8">
            <div v-for="n in 2" :key="n" class="relative">
              <span class="absolute -left-[29px] top-1.5 w-3 h-3 rounded-full bg-gray-200"></span>
              <div class="bg-white border border-gray-100 rounded-xl p-4 animate-pulse">
                <div class="h-2 bg-gray-100 rounded w-16 mb-2"></div>
                <div class="h-3 bg-gray-100 rounded w-full"></div>
              </div>
            </div>
          </div>

          <div v-else class="border-l-2 border-gray-100 pl-6 flex flex-col gap-6 md:gap-8">
            <div
              v-for="item in educationData"
              :key="item.id"
              class="relative group"
            >
              <span class="absolute -left-[29px] top-1.5 w-3 h-3 rounded-full bg-emerald-400 ring-4 ring-white group-hover:scale-125 transition-transform"></span>
              <div class="bg-white border border-gray-100 rounded-xl p-4 hover:border-gray-300 hover:shadow-sm transition-all">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-300 mb-1">{{ item.date }}</p>
                <h3 class="text-sm font-semibold text-gray-800 leading-relaxed">{{ item.title }}</h3>
              </div>
            </div>

            <!-- Empty state -->
            <div v-if="!educationPending && educationData.length === 0" class="text-xs text-gray-300 pl-2">
              No education added yet.
            </div>
          </div>
        </div>

        <!-- Experience -->
        <div>
          <div class="mb-8 md:mb-10">
            <span class="text-3xl">💼</span>
            <h2 class="text-xl md:text-2xl font-black text-black mt-2 tracking-tight">បទពិសោធន៍</h2>
            <p class="text-xs text-gray-400 uppercase tracking-[0.2em] font-semibold mt-1">Experience</p>
          </div>

          <!-- Loading -->
          <div v-if="experiencePending" class="border-l-2 border-gray-100 pl-6 flex flex-col gap-6 md:gap-8">
            <div v-for="n in 2" :key="n" class="relative">
              <span class="absolute -left-[29px] top-1.5 w-3 h-3 rounded-full bg-gray-200"></span>
              <div class="bg-white border border-gray-100 rounded-xl p-4 animate-pulse">
                <div class="h-2 bg-gray-100 rounded w-24 mb-2"></div>
                <div class="h-3 bg-gray-100 rounded w-full"></div>
              </div>
            </div>
          </div>

          <div v-else class="border-l-2 border-gray-100 pl-6 flex flex-col gap-6 md:gap-8">
            <div
              v-for="item in experienceData"
              :key="item.id"
              class="relative group"
            >
              <span class="absolute -left-[29px] top-1.5 w-3 h-3 rounded-full bg-blue-400 ring-4 ring-white group-hover:scale-125 transition-transform"></span>
              <div class="bg-white border border-gray-100 rounded-xl p-4 hover:border-gray-300 hover:shadow-sm transition-all">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-300 mb-1">{{ item.date }}</p>
                <h3 class="text-sm font-semibold text-gray-800 leading-relaxed">{{ item.title }}</h3>
              </div>
            </div>

            <!-- Empty state -->
            <div v-if="!experiencePending && experienceData.length === 0" class="text-xs text-gray-300 pl-2">
              No experience added yet.
            </div>
          </div>
        </div>

      </div>
    </section>

  </div>
</template>

<script setup>
const config = useRuntimeConfig()
const baseUrl = config.public.apiBase

const { data: educationData, pending: educationPending } = await useFetch(
  `${baseUrl}/api/education`,
  { default: () => [] }
)

const { data: experienceData, pending: experiencePending } = await useFetch(
  `${baseUrl}/api/experience`,
  { default: () => [] }
)
</script>