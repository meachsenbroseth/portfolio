<template>
  <div class="bg-white min-h-screen flex items-center px-6 py-16">
    <section class="max-w-6xl mx-auto w-full grid md:grid-cols-2 gap-12 md:gap-16 items-center">

      <!-- Image -->
      <div class="relative group">
        <div class="absolute -inset-2 border border-gray-100 rounded-3xl"></div>
        <div class="w-full aspect-[4/3] md:aspect-auto md:h-[520px] overflow-hidden rounded-2xl relative z-10">
          <img
            src="https://i.pinimg.com/1200x/03/dc/72/03dc721f50ae1c80b2d7152bc3e9226d.jpg"
            alt="Developer workspace"
            class="w-full h-full object-cover grayscale-[30%] group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700"
          />
        </div>
        <div class="absolute bottom-4 left-4 z-20 bg-black/80 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-widest px-3 py-2 rounded-full">
          ✦ Daily Inspiration
        </div>
      </div>

      <!-- Quote -->
      <div class="flex flex-col gap-6">

        <!-- Decorative quote mark — smaller on mobile -->
        <span class="text-7xl md:text-[120px] text-gray-100 font-black leading-none -mb-4 md:-mb-10 select-none">"</span>

        <Transition name="fade" mode="out-in">
          <div :key="current.id" class="flex flex-col gap-4 md:gap-5">
            <p class="text-xl md:text-2xl lg:text-3xl font-light leading-relaxed text-gray-800 tracking-tight">
              {{ current.quote }}
            </p>
            <div class="flex items-center gap-3">
              <span class="w-6 md:w-8 h-px bg-black"></span>
              <p class="text-xs font-bold tracking-[0.2em] uppercase text-gray-400">
                {{ current.author }}
              </p>
            </div>
          </div>
        </Transition>

        <!-- Auto-progress bar -->
        <div class="w-full h-px bg-gray-100 overflow-hidden rounded-full">
          <div
            class="h-full bg-black rounded-full"
            :style="{ width: progress + '%', transition: 'width 0.1s linear' }"
          ></div>
        </div>

        <!-- Dot indicators — hidden on very small screens -->
        <div class="hidden sm:flex flex-wrap gap-1.5">
          <span
            v-for="(_, i) in randomQuote"
            :key="i"
            class="h-1 rounded-full transition-all duration-300"
            :class="i === currentIndex ? 'w-6 bg-black' : 'w-1.5 bg-gray-200'"
          ></span>
        </div>

        <!-- Mobile: show current index instead of dots -->
        <p class="sm:hidden text-xs text-gray-300 font-semibold tabular-nums">
          {{ currentIndex + 1 }} / {{ randomQuote.length }}
        </p>

      </div>
    </section>
  </div>
</template>

<script setup>
const randomQuote = [
  { id: 1,  quote: "Be yourself; everyone else is already taken.",                                author: "Oscar Wilde" },
  { id: 2,  quote: "In the middle of difficulty lies opportunity.",                               author: "Albert Einstein" },
  { id: 3,  quote: "Do what you can, with what you have, where you are.",                        author: "Theodore Roosevelt" },
  { id: 4,  quote: "Dream big and dare to fail.",                                                 author: "Norman Vaughan" },
  { id: 5,  quote: "Turn your wounds into wisdom.",                                               author: "Oprah Winfrey" },
  { id: 6,  quote: "Happiness depends upon ourselves.",                                           author: "Aristotle" },
  { id: 7,  quote: "Whatever you are, be a good one.",                                           author: "Abraham Lincoln" },
  { id: 8,  quote: "Simplicity is the ultimate sophistication.",                                  author: "Leonardo da Vinci" },
  { id: 9,  quote: "Stay hungry, stay foolish.",                                                  author: "Steve Jobs" },
  { id: 10, quote: "The purpose of our lives is to be happy.",                                    author: "Dalai Lama" },
  { id: 11, quote: "Life is what happens when you're busy making other plans.",                   author: "John Lennon" },
  { id: 12, quote: "Success is not final, failure is not fatal.",                                 author: "Winston Churchill" },
  { id: 13, quote: "Believe you can and you're halfway there.",                                   author: "Theodore Roosevelt" },
  { id: 14, quote: "Act as if what you do makes a difference. It does.",                         author: "William James" },
  { id: 15, quote: "Quality is not an act, it is a habit.",                                       author: "Aristotle" },
  { id: 16, quote: "Well done is better than well said.",                                         author: "Benjamin Franklin" },
  { id: 17, quote: "The best revenge is massive success.",                                        author: "Frank Sinatra" },
  { id: 18, quote: "What we think, we become.",                                                   author: "Buddha" },
  { id: 19, quote: "Everything you can imagine is real.",                                         author: "Pablo Picasso" },
  { id: 20, quote: "Do one thing every day that scares you.",                                     author: "Eleanor Roosevelt" },
  { id: 21, quote: "The journey of a thousand miles begins with one step.",                      author: "Lao Tzu" },
  { id: 22, quote: "That which does not kill us makes us stronger.",                              author: "Friedrich Nietzsche" },
  { id: 23, quote: "Peace begins with a smile.",                                                  author: "Mother Teresa" },
  { id: 24, quote: "If opportunity doesn't knock, build a door.",                                author: "Milton Berle" },
  { id: 25, quote: "You miss 100% of the shots you don't take.",                                 author: "Wayne Gretzky" },
  { id: 26, quote: "Everything you've ever wanted is on the other side of fear.",               author: "George Addair" },
  { id: 27, quote: "Whatever you do, do it well.",                                               author: "Walt Disney" },
  { id: 28, quote: "No pressure, no diamonds.",                                                  author: "Thomas Carlyle" },
  { id: 29, quote: "To live is the rarest thing in the world. Most people exist.",              author: "Oscar Wilde" },
  { id: 30, quote: "I came, I saw, I conquered.",                                                author: "Julius Caesar" },
]

const INTERVAL = 10000

const currentIndex = ref(Math.floor(Math.random() * randomQuote.length))
const progress = ref(0)
const current = computed(() => randomQuote[currentIndex.value])

let progressTimer = null
let quoteTimer = null

function startCycle() {
  progress.value = 0

  progressTimer = setInterval(() => {
    progress.value += (100 / (INTERVAL / 100))
  }, 100)

  quoteTimer = setTimeout(() => {
    clearInterval(progressTimer)
    currentIndex.value = (currentIndex.value + 1) % randomQuote.length
    startCycle()
  }, INTERVAL)
}

onMounted(() => startCycle())
onUnmounted(() => {
  clearInterval(progressTimer)
  clearTimeout(quoteTimer)
})
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.4s, transform 0.4s; }
.fade-enter-from { opacity: 0; transform: translateY(12px); }
.fade-leave-to   { opacity: 0; transform: translateY(-12px); }
</style>