<template>
  <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">

      <!-- Logo -->
      <nuxt-link to="/" class="text-sm font-black tracking-[0.15em] uppercase text-black hover:opacity-60 transition-opacity">
        Seth
      </nuxt-link>

      <!-- Desktop Links -->
      <nav class="hidden md:flex items-center gap-1">
        <nuxt-link
          v-for="link in links"
          :key="link.path"
          :to="link.path"
          class="relative px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-gray-500 hover:text-black transition-colors duration-200 group"
        >
          {{ link.label }}
          <!-- underline indicator -->
          <span class="absolute bottom-0 left-4 right-4 h-px bg-black scale-x-0 group-hover:scale-x-100 transition-transform duration-200 origin-left"></span>
          <!-- active indicator via router-link-active -->
        </nuxt-link>
      </nav>

      <!-- Mobile hamburger -->
      <button
        @click="open = !open"
        class="md:hidden flex flex-col gap-1.5 p-2 group"
        aria-label="Toggle menu"
      >
        <span
          class="block w-5 h-px bg-black transition-all duration-300"
          :class="open ? 'rotate-45 translate-y-2' : ''"
        ></span>
        <span
          class="block w-5 h-px bg-black transition-all duration-300"
          :class="open ? 'opacity-0' : ''"
        ></span>
        <span
          class="block w-5 h-px bg-black transition-all duration-300"
          :class="open ? '-rotate-45 -translate-y-2' : ''"
        ></span>
      </button>
    </div>

    <!-- Mobile Menu -->
    <div
      class="md:hidden overflow-hidden transition-all duration-300 ease-in-out"
      :class="open ? 'max-h-64 border-t border-gray-100' : 'max-h-0'"
    >
      <nav class="flex flex-col px-6 py-4 gap-1">
        <nuxt-link
          v-for="link in links"
          :key="link.path"
          :to="link.path"
          @click="open = false"
          class="py-3 text-xs font-semibold uppercase tracking-widest text-gray-500 hover:text-black border-b border-gray-50 last:border-0 transition-colors"
        >
          {{ link.label }}
        </nuxt-link>
      </nav>
    </div>
  </header>
</template>

<script setup>
const open = ref(false)

const links = [
  { path: '/',        label: 'Home' },
  { path: '/about',   label: 'About' },
  { path: '/projects',label: 'Projects' },
  { path: '/blog',    label: 'Blog' },
]
</script>

<style scoped>
/* Active link style using Nuxt's router-link-exact-active */
:deep(.router-link-exact-active) {
  color: #000 !important;
}
:deep(.router-link-exact-active) span {
  transform: scaleX(1) !important;
}
</style>