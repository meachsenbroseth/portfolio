<template>
  <div class="p-6 lg:p-8">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center gap-4">
        <nuxt-link
          to="/admin/blogs"
          class="p-2 hover:bg-gray-100 rounded-lg transition-colors"
        >
          <Icon name="mdi:arrow-left" size="18" class="text-gray-500" />
        </nuxt-link>
        <div>
          <h1 class="text-2xl font-black text-gray-900">{{ isEdit ? 'Edit Post' : 'Create Blog Post' }}</h1>
          <p class="text-sm text-gray-400 mt-0.5">Write and publish your content</p>
        </div>
      </div>
      <div class="flex gap-2">
        <button 
          @click="preview" 
          class="inline-flex items-center gap-2 border border-gray-200 text-gray-600 text-xs font-bold uppercase tracking-widest px-4 py-2.5 rounded-full hover:border-black hover:text-black transition-colors"
        >
          <Icon name="mdi:eye-outline" size="14" />
          Preview
        </button>
        <button 
          @click="saveDraft" 
          :disabled="loading"
          class="inline-flex items-center gap-2 border border-gray-200 text-gray-600 text-xs font-bold uppercase tracking-widest px-4 py-2.5 rounded-full hover:border-black hover:text-black transition-colors disabled:opacity-50"
        >
          <Icon name="mdi:content-save-outline" size="14" />
          {{ loading ? 'Saving...' : 'Save Draft' }}
        </button>
        <button 
          @click="publish" 
          :disabled="loading"
          class="inline-flex items-center gap-2 bg-black text-white text-xs font-bold uppercase tracking-widest px-4 py-2.5 rounded-full hover:bg-gray-800 transition-colors disabled:opacity-50"
        >
          <Icon name="mdi:send" size="14" />
          {{ loading ? 'Publishing...' : (isEdit ? 'Update' : 'Publish') }}
        </button>
      </div>
    </div>

    <!-- Global Error Message -->
    <div v-if="error" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
      <Icon name="mdi:alert-circle" size="20" class="text-red-500 shrink-0 mt-0.5" />
      <div class="flex-1">
        <p class="text-sm text-red-700 font-medium">{{ error }}</p>
      </div>
      <button @click="error = ''" class="text-red-400 hover:text-red-600">
        <Icon name="mdi:close" size="16" />
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="isLoading" class="flex items-center justify-center py-12">
      <div class="flex flex-col items-center gap-3">
        <div class="w-8 h-8 border-2 border-gray-200 border-t-black rounded-full animate-spin"></div>
        <p class="text-sm text-gray-400">Loading blog post...</p>
      </div>
    </div>

    <!-- Form Content -->
    <div v-else class="grid lg:grid-cols-3 gap-6">

      <!-- Main Content -->
      <div class="lg:col-span-2 flex flex-col gap-6">

        <!-- Title & Excerpt & Content -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 flex flex-col gap-6">

          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Title *</label>
            <input
              v-model="form.title"
              type="text"
              placeholder="Enter your blog post title..."
              :class="[
                'w-full text-2xl font-bold text-gray-900 placeholder-gray-200 border rounded-lg px-4 py-3 focus:outline-none focus:border-black transition-colors',
                errors.title ? 'border-red-300 focus:border-red-500' : 'border-gray-200'
              ]"
            />
            <p v-if="errors.title" class="text-xs text-red-500 mt-1">{{ errors.title }}</p>
          </div>

          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Excerpt</label>
            <textarea
              v-model="form.excerpt"
              rows="3"
              placeholder="Brief description of your post (shown in previews)..."
              class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm text-gray-600 placeholder-gray-300 focus:outline-none focus:border-black transition-colors resize-none"
            ></textarea>
          </div>

          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Content *</label>
            <textarea
              v-model="form.content"
              rows="20"
              placeholder="Write your blog post content here... Supports Markdown."
              :class="[
                'w-full border rounded-lg px-4 py-3 text-sm text-gray-600 placeholder-gray-300 focus:outline-none focus:border-black transition-colors resize-y font-mono',
                errors.content ? 'border-red-300 focus:border-red-500' : 'border-gray-200'
              ]"
              @input="autoResize"
            ></textarea>
            <p v-if="errors.content" class="text-xs text-red-500 mt-1">{{ errors.content }}</p>
            <p class="text-[10px] text-gray-400">Markdown supported. Use **bold**, *italic*, # headings, etc.</p>
          </div>

        </div>

        <!-- SEO -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 flex flex-col gap-5">
          <h2 class="text-sm font-black text-gray-900 uppercase tracking-widest">SEO & Meta</h2>

          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Meta Title</label>
            <input
              v-model="form.meta_title"
              type="text"
              maxlength="60"
              placeholder="SEO optimized title (max 60 characters)"
              :class="[
                'w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-black transition-colors',
                errors.meta_title ? 'border-red-300 focus:border-red-500' : 'border-gray-200'
              ]"
            />
            <p v-if="errors.meta_title" class="text-xs text-red-500 mt-1">{{ errors.meta_title }}</p>
            <p class="text-[10px] text-gray-300 text-right">{{ form.meta_title?.length ?? 0 }}/60</p>
          </div>

          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Meta Description</label>
            <textarea
              v-model="form.meta_description"
              rows="3"
              maxlength="160"
              placeholder="SEO optimized description (max 160 characters)"
              :class="[
                'w-full border rounded-lg px-4 py-3 text-sm text-gray-600 placeholder-gray-300 focus:outline-none focus:border-black transition-colors resize-none',
                errors.meta_description ? 'border-red-300 focus:border-red-500' : 'border-gray-200'
              ]"
            ></textarea>
            <p v-if="errors.meta_description" class="text-xs text-red-500 mt-1">{{ errors.meta_description }}</p>
            <p class="text-[10px] text-gray-300 text-right">{{ form.meta_description?.length ?? 0 }}/160</p>
          </div>

          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">URL Slug</label>
            <input
              v-model="form.slug"
              type="text"
              placeholder="url-friendly-slug"
              :class="[
                'w-full border rounded-lg px-4 py-2.5 text-sm font-mono focus:outline-none focus:border-black transition-colors',
                errors.slug ? 'border-red-300 focus:border-red-500' : 'border-gray-200'
              ]"
            />
            <p v-if="errors.slug" class="text-xs text-red-500 mt-1">{{ errors.slug }}</p>
            <p class="text-[10px] text-gray-400">Auto-generated from title if left empty</p>
          </div>
        </div>

      </div>

      <!-- Sidebar -->
      <div class="flex flex-col gap-5">

        <!-- Status -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 flex flex-col gap-4">
          <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Status</h3>
          <div class="flex items-center gap-2">
            <span class="text-xs text-gray-500">Current:</span>
            <span
              class="text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full"
              :class="form.status === 'published' ? 'bg-black text-white' : 'bg-gray-100 text-gray-500'"
            >
              {{ form.status }}
            </span>
          </div>
          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Publish Date *</label>
            <input
              v-model="form.published_at"
              type="date"
              :class="[
                'w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors',
                errors.published_at ? 'border-red-300 focus:border-red-500' : 'border-gray-200'
              ]"
            />
            <p v-if="errors.published_at" class="text-xs text-red-500 mt-1">{{ errors.published_at }}</p>
          </div>
          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Author</label>
            <input
              v-model="form.author"
              type="text"
              placeholder="Author name"
              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors"
            />
          </div>
          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Read Time (min)</label>
            <input
              v-model="form.read_time"
              type="number"
              placeholder="5"
              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors"
            />
          </div>
        </div>

        <!-- Featured Image -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 flex flex-col gap-4">
          <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Featured Image</h3>

          <div
            v-if="!coverPreview"
            @click="imageInput.click()"
            @dragover.prevent="dragOver = true"
            @dragleave="dragOver = false"
            @drop.prevent="handleDrop"
            class="aspect-video border-2 border-dashed rounded-xl flex flex-col items-center justify-center gap-2 cursor-pointer transition-colors"
            :class="dragOver ? 'border-black bg-gray-50' : (errors.cover_image ? 'border-red-300' : 'border-gray-200 hover:border-gray-400')"
          >
            <Icon name="mdi:cloud-upload-outline" size="28" class="text-gray-300" />
            <p class="text-xs font-medium text-gray-400">Click to upload or drag & drop</p>
            <p class="text-[10px] text-gray-300">PNG, JPG, WEBP up to 2MB</p>
          </div>

          <div v-else class="relative group rounded-xl overflow-hidden">
            <img :src="coverPreview" alt="Cover" class="w-full aspect-video object-cover" />
            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
              <button @click="imageInput.click()" class="bg-white text-black text-xs font-bold px-3 py-1.5 rounded-full">Change</button>
              <button @click="removeCover" class="bg-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-full">Remove</button>
            </div>
          </div>

          <input ref="imageInput" type="file" accept="image/jpeg,image/png,image/webp" class="hidden" @change="handleImageUpload" />
          <p v-if="errors.cover_image" class="text-xs text-red-500 mt-1">{{ errors.cover_image }}</p>

          <button @click="imageInput.click()" class="w-full inline-flex items-center justify-center gap-2 border border-gray-200 text-gray-600 text-xs font-bold uppercase tracking-widest py-2.5 rounded-full hover:border-black hover:text-black transition-colors">
            <Icon name="mdi:upload" size="14" />
            Upload Image
          </button>
        </div>

        <!-- Category -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 flex flex-col gap-3">
          <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Category</h3>
          <select
            v-model="form.category"
            :class="[
              'w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors bg-white',
              errors.category ? 'border-red-300 focus:border-red-500' : 'border-gray-200'
            ]"
          >
            <option value="">Choose a category...</option>
            <option v-for="cat in categories" :key="cat" :value="cat.toLowerCase()">{{ cat }}</option>
          </select>
          <p v-if="errors.category" class="text-xs text-red-500 mt-1">{{ errors.category }}</p>
        </div>

        <!-- Tags -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 flex flex-col gap-3">
          <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Tags</h3>
          <div class="flex flex-wrap gap-1.5 min-h-8">
            <span
              v-for="tag in form.tags"
              :key="tag"
              class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 text-xs font-medium px-2.5 py-1 rounded-full"
            >
              {{ tag }}
              <button @click="removeTag(tag)" class="hover:text-red-500 transition-colors">
                <Icon name="mdi:close" size="10" />
              </button>
            </span>
          </div>
          <div class="flex gap-2">
            <input
              v-model="tagInput"
              type="text"
              placeholder="Add a tag..."
              @keydown.enter.prevent="addTag"
              class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors"
            />
            <button @click="addTag" class="p-2 border border-gray-200 rounded-lg hover:border-black hover:text-black transition-colors">
              <Icon name="mdi:plus" size="16" class="text-gray-500" />
            </button>
          </div>
          <p v-if="errors.tags" class="text-xs text-red-500 mt-1">{{ errors.tags }}</p>
        </div>

        <!-- Visibility -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 flex flex-col gap-3">
          <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Visibility</h3>
          <label v-for="v in visibilities" :key="v.value" class="flex items-start gap-3 cursor-pointer group">
            <input type="radio" v-model="form.visibility" :value="v.value" class="mt-0.5 accent-black" />
            <div>
              <p class="text-sm font-semibold text-gray-700 group-hover:text-black transition-colors">{{ v.label }}</p>
              <p class="text-[10px] text-gray-400">{{ v.desc }}</p>
            </div>
          </label>
        </div>

        <!-- Danger -->
        <div v-if="isEdit" class="bg-white rounded-2xl border border-red-100 p-5 flex flex-col gap-3">
          <h3 class="text-xs font-black uppercase tracking-widest text-red-400">Danger Zone</h3>
          <button @click="deletePost" class="w-full text-xs font-bold text-red-500 border border-red-200 rounded-full py-2 hover:bg-red-50 transition-colors">
            Delete Post
          </button>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
definePageMeta({ layout: 'admin-layout' })

const route = useRoute()
const config = useRuntimeConfig()
const client = useSanctumClient()

const isEdit = computed(() => !!route.query.slug || !!route.query.id)

const imageInput = ref(null)
const coverPreview = ref(null)
const imageFile = ref(null)
const dragOver = ref(false)
const tagInput = ref('')
const loading = ref(false)
const error = ref('')
const isLoading = ref(true)
const currentPostId = ref(null)

const errors = ref({
  title: '',
  content: '',
  category: '',
  published_at: '',
  cover_image: '',
  meta_title: '',
  meta_description: '',
  slug: '',
  tags: ''
})

const form = ref({
  title: '',
  excerpt: '',
  content: '',
  meta_title: '',
  meta_description: '',
  slug: '',
  status: 'draft',
  published_at: new Date().toISOString().split('T')[0],
  author: 'Seth',
  read_time: 5,
  category: '',
  tags: [],
  visibility: 'public',
})

const categories = ['Tutorial', 'Opinion', 'CSS', 'JavaScript', 'Vue', 'Nuxt', 'React', 'Laravel', 'PHP']

const visibilities = [
  { value: 'public',   label: 'Public',             desc: 'Visible to everyone' },
  { value: 'private',  label: 'Private',            desc: 'Only visible to you' },
  { value: 'password', label: 'Password Protected', desc: 'Requires password to view' },
]

// Auto-generate slug from title
watch(() => form.value.title, (val) => {
  if (!isEdit.value && val) {
    form.value.slug = val.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '')
  }
})

// Load blog post data if editing
async function loadPostData() {
  if (!isEdit.value) {
    isLoading.value = false
    return
  }
  
  isLoading.value = true
  try {
    const slug = route.query.slug || route.query.id
    
    if (!slug) {
      throw new Error('No post slug provided')
    }
    
    console.log('Loading post with slug:', slug)
    
    const url = `${config.public.apiBase}/api/blogs/${slug}`
    const response = await $fetch(url)
    
    console.log('API Response:', response)
    
    const post = response?.data || response
    
    if (post && post.id) {
      currentPostId.value = post.id
      
      // Handle tags - could be array or JSON string
      let tags = post.tags || []
      if (typeof tags === 'string') {
        try {
          tags = JSON.parse(tags)
        } catch (e) {
          tags = tags.split(',').map(t => t.trim()).filter(t => t)
        }
      }
      
      form.value = {
        title: post.title || '',
        excerpt: post.excerpt || '',
        content: post.content || '',
        meta_title: post.meta_title || '',
        meta_description: post.meta_description || '',
        slug: post.slug || '',
        status: post.status || 'draft',
        published_at: post.published_at ? post.published_at.split('T')[0] : new Date().toISOString().split('T')[0],
        author: post.author || 'Seth',
        read_time: post.read_time || 5,
        category: post.category || '',
        tags: Array.isArray(tags) ? tags : [],
        visibility: post.visibility || 'public',
      }
      
      // Handle image
      const imageUrl = post.cover_image || post.image
      if (imageUrl) {
        if (imageUrl.startsWith('http')) {
          coverPreview.value = imageUrl
        } else {
          coverPreview.value = `${config.public.apiBase}/storage/${imageUrl}`
        }
      }
    } else {
      throw new Error('Post not found')
    }
  } catch (err) {
    console.error('Error loading post:', err)
    error.value = `Failed to load post: ${err.message || 'Please check if the post exists.'}`
  } finally {
    isLoading.value = false
  }
}

// Set validation errors from API response
function setValidationErrors(apiErrors) {
  errors.value = {
    title: '',
    content: '',
    category: '',
    published_at: '',
    cover_image: '',
    meta_title: '',
    meta_description: '',
    slug: '',
    tags: ''
  }
  
  if (apiErrors && typeof apiErrors === 'object') {
    Object.keys(apiErrors).forEach(key => {
      if (errors.value.hasOwnProperty(key)) {
        errors.value[key] = Array.isArray(apiErrors[key]) ? apiErrors[key][0] : apiErrors[key]
      }
    })
  }
}

function autoResize(e) {
  e.target.style.height = 'auto'
  e.target.style.height = e.target.scrollHeight + 'px'
}

// Image handling
function handleImageUpload(e) {
  const file = e.target.files[0]
  if (!file) return
  
  const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp']
  if (!validTypes.includes(file.type)) {
    errors.value.cover_image = 'Please upload a valid image file (JPG, PNG, WEBP)'
    return
  }
  
  if (file.size > 2 * 1024 * 1024) {
    errors.value.cover_image = 'Image must be less than 2MB'
    return
  }
  
  errors.value.cover_image = ''
  imageFile.value = file
  coverPreview.value = URL.createObjectURL(file)
}

function handleDrop(e) {
  dragOver.value = false
  const file = e.dataTransfer.files[0]
  if (!file || !file.type.startsWith('image/')) return
  handleImageUpload({ target: { files: [file] } })
}

function removeCover() {
  coverPreview.value = null
  imageFile.value = null
  if (imageInput.value) imageInput.value.value = ''
  errors.value.cover_image = ''
}

// Tags
function addTag() {
  const t = tagInput.value.trim()
  if (t && !form.value.tags.includes(t)) {
    form.value.tags.push(t)
  }
  tagInput.value = ''
  errors.value.tags = ''
}

function removeTag(tag) {
  form.value.tags = form.value.tags.filter(t => t !== tag)
}

// Preview modal (simple implementation)
function preview() {
  if (!form.value.content) {
    error.value = 'Please add some content before previewing'
    return
  }
  // You can implement a modal preview here
  window.open(`/blog/preview?content=${encodeURIComponent(form.value.content)}`, '_blank')
}

// Submit
async function submit(status) {
  error.value = ''
  loading.value = true
  form.value.status = status

  errors.value = {
    title: '',
    content: '',
    category: '',
    published_at: '',
    cover_image: '',
    meta_title: '',
    meta_description: '',
    slug: '',
    tags: ''
  }

  try {
    const formData = new FormData()

    formData.append('title', form.value.title)
    formData.append('excerpt', form.value.excerpt)
    formData.append('content', form.value.content)
    formData.append('category', form.value.category)
    formData.append('published_at', form.value.published_at)
    formData.append('status', form.value.status)
    formData.append('meta_title', form.value.meta_title)
    formData.append('meta_description', form.value.meta_description)
    formData.append('slug', form.value.slug)
    formData.append('author', form.value.author)
    formData.append('read_time', form.value.read_time)
    formData.append('visibility', form.value.visibility)

    if (form.value.tags.length > 0) {
      formData.append('tags', JSON.stringify(form.value.tags))
    } else {
      formData.append('tags', JSON.stringify([]))
    }

    if (imageFile.value) {
      formData.append('cover_image', imageFile.value)
    }

    if (isEdit.value) {
      if (!currentPostId.value) {
        throw new Error('Post ID not found')
      }
      
      formData.append('_method', 'PUT')
      await client(`${config.public.apiBase}/api/blogs/${currentPostId.value}`, {
        method: 'POST',
        body: formData,
      })
    } else {
      await client(`${config.public.apiBase}/api/blogs`, {
        method: 'POST',
        body: formData,
      })
    }

    navigateTo('/admin/blogs')

  } catch (e) {
    console.error('Submit error:', e)
    if (e?.response?.status === 422 && e?.data?.errors) {
      setValidationErrors(e.data.errors)
      error.value = 'Please fix the errors below before continuing.'
    } else if (e?.data?.message) {
      error.value = e.data.message
    } else {
      error.value = 'Something went wrong. Please try again.'
    }
  } finally {
    loading.value = false
  }
}

function saveDraft() { 
  submit('draft') 
}

function publish() { 
  submit('published') 
}

// Delete
async function deletePost() {
  if (!confirm('Are you sure you want to delete this post?')) return
  
  try {
    if (!currentPostId.value) {
      throw new Error('Post ID not found')
    }
    
    await client(`${config.public.apiBase}/api/blogs/${currentPostId.value}`, {
      method: 'DELETE',
    })
    navigateTo('/admin/blogs')
  } catch (e) {
    console.error('Delete error:', e)
    error.value = 'Failed to delete post. Please try again.'
  }
}

// Load data on mount
onMounted(() => {
  loadPostData()
})
</script>