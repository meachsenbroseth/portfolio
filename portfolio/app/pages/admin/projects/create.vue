<template>
  <div class="p-6 lg:p-8">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center gap-4">
        <nuxt-link to="/admin/projects" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
          <Icon name="mdi:arrow-left" size="18" class="text-gray-500" />
        </nuxt-link>

        <div>
          <h1 class="text-2xl font-black text-gray-900">
            {{ isEdit ? 'Edit Project' : 'Create Project' }}
          </h1>
          <p class="text-sm text-gray-400 mt-0.5">
            Fill in your project details
          </p>
        </div>
      </div>

      <div class="flex gap-2">

        <!-- Save Draft -->
        <button type="button" @click="saveDraft" :disabled="loading"
          class="inline-flex items-center gap-2 border border-gray-200 text-gray-600 text-xs font-bold uppercase tracking-widest px-4 py-2.5 rounded-full hover:border-black hover:text-black transition-colors disabled:opacity-50">
          <Icon name="mdi:content-save-outline" size="14" />
          {{ loading ? 'Saving...' : 'Save Draft' }}
        </button>

        <!-- Publish -->
        <button type="button" @click="publish" :disabled="loading"
          class="inline-flex items-center gap-2 bg-black text-white text-xs font-bold uppercase tracking-widest px-4 py-2.5 rounded-full hover:bg-gray-800 transition-colors disabled:opacity-50">
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
        <p class="text-sm text-gray-400">Loading project data...</p>
      </div>
    </div>

    <!-- Form Content -->
    <div v-else class="grid lg:grid-cols-3 gap-6">

      <!-- Main -->
      <div class="lg:col-span-2 flex flex-col gap-6">

        <!-- Basic Info -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 flex flex-col gap-5">
          <h2 class="text-xs font-black uppercase tracking-widest text-gray-400">Basic Info</h2>

          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Title *</label>
            <input v-model="form.title" type="text" placeholder="E-Commerce Platform..."
              :class="[
                'w-full text-xl font-bold text-gray-900 placeholder-gray-200 border rounded-lg px-4 py-3 focus:outline-none focus:border-black transition-colors',
                errors.title ? 'border-red-300 focus:border-red-500' : 'border-gray-200'
              ]" />
            <p v-if="errors.title" class="text-xs text-red-500 mt-1">{{ errors.title }}</p>
          </div>

          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Short Description</label>
            <input v-model="form.short_description" type="text" placeholder="One-liner shown in project cards..."
              class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-600 placeholder-gray-300 focus:outline-none focus:border-black transition-colors" />
          </div>

          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Full Description *</label>
            <textarea v-model="form.description" rows="6"
              placeholder="Detailed description of the project, what it does, how it works..."
              :class="[
                'w-full border rounded-lg px-4 py-3 text-sm text-gray-600 placeholder-gray-300 focus:outline-none focus:border-black transition-colors resize-y',
                errors.description ? 'border-red-300 focus:border-red-500' : 'border-gray-200'
              ]"></textarea>
            <p v-if="errors.description" class="text-xs text-red-500 mt-1">{{ errors.description }}</p>
          </div>
        </div>

        <!-- Technologies -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 flex flex-col gap-4">
          <h2 class="text-xs font-black uppercase tracking-widest text-gray-400">Technologies</h2>
          <div class="flex flex-wrap gap-2 min-h-8">
            <span v-for="tech in form.technologies" :key="tech"
              class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 text-xs font-semibold px-3 py-1.5 rounded-full">
              {{ tech }}
              <button @click="removeTech(tech)" class="hover:text-red-500 transition-colors">
                <Icon name="mdi:close" size="11" />
              </button>
            </span>
          </div>
          <div class="flex gap-2">
            <input v-model="techInput" type="text" placeholder="React, Node.js, Tailwind..."
              @keydown.enter.prevent="addTech"
              class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors" />
            <button @click="addTech"
              class="p-2 border border-gray-200 rounded-lg hover:border-black hover:text-black transition-colors">
              <Icon name="mdi:plus" size="16" class="text-gray-500" />
            </button>
          </div>
          <!-- Quick add suggestions -->
          <div class="flex flex-wrap gap-1.5">
            <button v-for="s in suggestions.filter(s => !form.technologies.includes(s))" :key="s"
              @click="form.technologies.push(s)"
              class="text-[10px] font-medium text-gray-400 border border-gray-100 px-2.5 py-1 rounded-full hover:border-black hover:text-black transition-colors">
              + {{ s }}
            </button>
          </div>
          <p v-if="errors.technologies" class="text-xs text-red-500 mt-1">{{ errors.technologies }}</p>
        </div>

        <!-- Links -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 flex flex-col gap-4">
          <h2 class="text-xs font-black uppercase tracking-widest text-gray-400">Links</h2>
          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">GitHub URL</label>
            <div
              :class="[
                'flex items-center gap-2 border rounded-lg px-4 py-2.5 focus-within:border-black transition-colors',
                errors.github_link ? 'border-red-300 focus-within:border-red-500' : 'border-gray-200'
              ]">
              <Icon name="mdi:github" size="16" class="text-gray-400 shrink-0" />
              <input v-model="form.github_url" type="url" placeholder="https://github.com/username/repo"
                class="flex-1 text-sm text-gray-600 placeholder-gray-300 focus:outline-none bg-transparent" />
            </div>
            <p v-if="errors.github_link" class="text-xs text-red-500 mt-1">{{ errors.github_link }}</p>
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
            <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full"
              :class="form.status === 'published' ? 'bg-black text-white' : 'bg-gray-100 text-gray-500'">
              {{ form.status }}
            </span>
          </div>
          <div class="flex flex-col gap-1.5">
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Date *</label>
            <input v-model="form.date" type="date" placeholder="January 2026"
              :class="[
                'w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors',
                errors.date ? 'border-red-300 focus:border-red-500' : 'border-gray-200'
              ]" />
            <p v-if="errors.date" class="text-xs text-red-500 mt-1">{{ errors.date }}</p>
          </div>
        </div>

        <!-- Cover Image -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 flex flex-col gap-4">
          <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Cover Image</h3>
          <div v-if="!coverPreview" @click="imageInput.click()" @dragover.prevent="dragOver = true"
            @dragleave="dragOver = false" @drop.prevent="handleDrop"
            class="aspect-video border-2 border-dashed rounded-xl flex flex-col items-center justify-center gap-2 cursor-pointer transition-colors"
            :class="dragOver ? 'border-black bg-gray-50' : (errors.image ? 'border-red-300' : 'border-gray-200 hover:border-gray-400')">
            <Icon name="mdi:image-plus" size="26" class="text-gray-300" />
            <p class="text-xs font-medium text-gray-400">Click or drag & drop</p>
            <p class="text-[10px] text-gray-300">PNG, JPG, WEBP up to 2MB</p>
          </div>
          <div v-else class="relative group rounded-xl overflow-hidden">
            <img :src="coverPreview" class="w-full aspect-video object-cover" />
            <div
              class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
              <button @click="imageInput.click()"
                class="bg-white text-black text-xs font-bold px-3 py-1.5 rounded-full">Change</button>
              <button @click="removeCover"
                class="bg-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-full">Remove</button>
            </div>
          </div>
          <input ref="imageInput" type="file" accept="image/jpeg,image/png,image/webp" class="hidden" @change="handleImageUpload" />
          <button @click="imageInput.click()"
            class="w-full inline-flex items-center justify-center gap-2 border border-gray-200 text-gray-600 text-xs font-bold uppercase tracking-widest py-2.5 rounded-full hover:border-black hover:text-black transition-colors">
            <Icon name="mdi:upload" size="14" />
            Upload Image
          </button>
          <p v-if="errors.image" class="text-xs text-red-500 mt-1">{{ errors.image }}</p>
        </div>

        <!-- Category -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 flex flex-col gap-3">
          <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Category *</h3>
          <select v-model="form.category"
            :class="[
              'w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors bg-white',
              errors.category ? 'border-red-300 focus:border-red-500' : 'border-gray-200'
            ]">
            <option value="">Choose a category...</option>
            <option v-for="cat in categories" :key="cat">{{ cat }}</option>
          </select>
          <p v-if="errors.category" class="text-xs text-red-500 mt-1">{{ errors.category }}</p>
        </div>

        <!-- Danger -->
        <div v-if="isEdit" class="bg-white rounded-2xl border border-red-100 p-5 flex flex-col gap-3">
          <h3 class="text-xs font-black uppercase tracking-widest text-red-400">Danger Zone</h3>
          <button @click="deleteProject"
            class="w-full text-xs font-bold text-red-500 border border-red-200 rounded-full py-2 hover:bg-red-50 transition-colors">
            Delete Project
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
const techInput = ref('')
const loading = ref(false)
const error = ref('')
const isLoading = ref(true)
const currentProjectId = ref(null) // Store the project ID for updates

const errors = ref({
  title: '',
  description: '',
  category: '',
  date: '',
  technologies: '',
  github_link: '',
  image: ''
})

const form = ref({
  title: '',
  short_description: '',
  description: '',
  category: '',
  date: '',
  status: 'draft',
  technologies: [],
  github_url: '',
  live_url: '',
})

const categories = ['Web Development', 'Mobile Development', 'UI/UX Design', 'Branding', 'Backend', 'Full Stack']
const suggestions = ['Vue', 'Nuxt', 'React', 'Node.js', 'TypeScript', 'Tailwind', 'PostgreSQL', 'MongoDB', 'Firebase', 'Stripe', 'AWS']

// Function to load project data
async function loadProjectData() {
  if (!isEdit.value) {
    isLoading.value = false
    return
  }
  
  isLoading.value = true
  try {
    // Get the slug from query parameters
    const slug = route.query.slug || route.query.id
    
    if (!slug) {
      throw new Error('No project slug provided')
    }
    
    console.log('Loading project with slug:', slug)
    
    // Use your existing route that accepts slug
    const url = `${config.public.apiBase}/api/projects/${slug}`
    console.log('Fetching from URL:', url)
    
    const response = await $fetch(url)
    
    console.log('API Response:', response)
    
    // Extract the project data from the response
    const project = response?.data || response
    
    console.log('Project data:', project)
    
    if (project && project.id) {
      // Store the actual project ID for updates
      currentProjectId.value = project.id
      
      // Handle technologies - could be array or JSON string
      let technologies = project.technologies || []
      if (typeof technologies === 'string') {
        try {
          technologies = JSON.parse(technologies)
        } catch (e) {
          // If JSON parse fails, try splitting by comma
          technologies = technologies.split(',').map(t => t.trim()).filter(t => t)
        }
      }
      
      // Populate the form
      form.value = {
        title: project.title || '',
        short_description: project.short_description || '',
        description: project.description || '',
        category: project.category || '',
        date: project.date || '',
        status: project.status || 'draft',
        technologies: Array.isArray(technologies) ? technologies : [],
        github_url: project.github_link || project.github_url || '',
        live_url: project.live_url || '',
      }
      
      console.log('Form populated:', form.value)
      
      // Handle image URL
      const imageUrl = project.image || project.cover_image || project.coverImage
      if (imageUrl) {
        if (imageUrl.startsWith('http')) {
          coverPreview.value = imageUrl
        } else {
          coverPreview.value = `${config.public.apiBase}/storage/${imageUrl}`
        }
        console.log('Cover image URL:', coverPreview.value)
      }
    } else {
      throw new Error('Project not found or invalid data structure')
    }
  } catch (err) {
    console.error('Error loading project:', err)
    error.value = `Failed to load project: ${err.message || 'Please check if the project exists.'}`
  } finally {
    isLoading.value = false
  }
}

// Load data when component mounts
onMounted(() => {
  loadProjectData()
})

// Set validation errors from API response
function setValidationErrors(apiErrors) {
  errors.value = {
    title: '',
    description: '',
    category: '',
    date: '',
    technologies: '',
    github_link: '',
    image: ''
  }
  
  if (apiErrors && typeof apiErrors === 'object') {
    Object.keys(apiErrors).forEach(key => {
      if (errors.value.hasOwnProperty(key)) {
        errors.value[key] = Array.isArray(apiErrors[key]) ? apiErrors[key][0] : apiErrors[key]
      }
    })
  }
}

// Technologies
function addTech() {
  const t = techInput.value.trim()
  if (t && !form.value.technologies.includes(t)) {
    form.value.technologies.push(t)
  }
  techInput.value = ''
  errors.value.technologies = ''
}

function removeTech(tech) {
  form.value.technologies = form.value.technologies.filter(t => t !== tech)
}

// Image
function handleImageUpload(e) {
  const file = e.target.files[0]
  if (!file) return
  
  // Validate file type
  const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp']
  if (!validTypes.includes(file.type)) {
    errors.value.image = 'Please upload a valid image file (JPG, PNG, WEBP)'
    return
  }
  
  // Validate file size (2MB)
  if (file.size > 2 * 1024 * 1024) {
    errors.value.image = 'Image must be less than 2MB'
    return
  }
  
  errors.value.image = ''
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
  errors.value.image = ''
}

// Submit
async function submit(status) {
  error.value = ''
  loading.value = true
  form.value.status = status

  // Clear all previous errors
  errors.value = {
    title: '',
    description: '',
    category: '',
    date: '',
    technologies: '',
    github_link: '',
    image: ''
  }

  try {
    const formData = new FormData()

    formData.append('title', form.value.title)
    formData.append('short_description', form.value.short_description)
    formData.append('description', form.value.description)
    formData.append('category', form.value.category)
    formData.append('date', form.value.date)
    formData.append('status', form.value.status)
    formData.append('github_link', form.value.github_url)

    if (form.value.technologies.length > 0) {
      formData.append('technologies', JSON.stringify(form.value.technologies))
    } else {
      formData.append('technologies', JSON.stringify([]))
    }

    if (imageFile.value) {
      formData.append('image', imageFile.value)
    }

    if (isEdit.value) {
      // Use the stored project ID for update
      if (!currentProjectId.value) {
        throw new Error('Project ID not found')
      }
      
      formData.append('_method', 'PUT')
      await client(`${config.public.apiBase}/api/projects/${currentProjectId.value}`, {
        method: 'POST',
        body: formData,
      })
    } else {
      await client(`${config.public.apiBase}/api/projects`, {
        method: 'POST',
        body: formData,
      })
    }

    navigateTo('/admin/projects')

  } catch (e) {
    console.error('Submit error:', e)
    // Handle validation errors from Laravel
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
async function deleteProject() {
  if (!confirm('Are you sure you want to delete this project?')) return
  
  try {
    if (!currentProjectId.value) {
      throw new Error('Project ID not found')
    }
    
    await client(`${config.public.apiBase}/api/projects/${currentProjectId.value}`, {
      method: 'DELETE',
    })
    navigateTo('/admin/projects')
  } catch (e) {
    console.error('Delete error:', e)
    error.value = 'Failed to delete project. Please try again.'
  }
}
</script>