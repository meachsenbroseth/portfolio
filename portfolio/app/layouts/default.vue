<template>
  <div class="min-h-screen flex flex-col">
    <header>
      <navbar />
    </header>

    <!-- Loading bar outside header, directly after it -->
    <NuxtLoadingIndicator
      color="#000000"
      :height="2"
      :duration="3000"
      :throttle="200"
    />

    <main class="flex-1">
      <slot />
    </main>

    <FooterComponent />
  </div>
</template>