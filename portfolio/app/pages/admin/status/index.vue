<template>
  <div>

    <!-- Header -->
    <div class="mb-8">
      <h2 class="text-2xl font-black text-gray-900">Update Status</h2>
      <p class="text-sm text-gray-400 mt-0.5">Manage your education and experience</p>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">

      <!-- Education -->
      <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
          <div class="flex items-center gap-2">
            <span class="text-lg">🎓</span>
            <h3 class="font-black text-gray-900">Education</h3>
          </div>
          <button @click="openModal('education')"
            class="inline-flex items-center gap-1.5 bg-black text-white text-[10px] font-black uppercase tracking-widest px-3 py-2 rounded-full hover:bg-gray-800 transition-colors">
            <Icon name="mdi:plus" size="12" />
            Add
          </button>
        </div>

        <div class="divide-y divide-gray-50">
          <div v-for="item in educationData" :key="item.id"
            class="flex items-start justify-between gap-4 px-6 py-4 hover:bg-gray-50 transition-colors group">
            <div class="flex items-start gap-3">
              <span class="mt-1.5 w-2 h-2 rounded-full bg-emerald-400 shrink-0"></span>
              <div>
                <p class="text-sm font-semibold text-gray-800 leading-snug">{{ item.title }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ item.date }}</p>
              </div>
            </div>
            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity shrink-0">
              <button @click="editItem('education', item)" class="p-1.5 hover:bg-gray-100 rounded-lg transition-colors">
                <Icon name="mdi:pencil" size="13" class="text-gray-400" />
              </button>
              <button @click="deleteItem('education', item.id)"
                class="p-1.5 hover:bg-red-50 rounded-lg transition-colors">
                <Icon name="mdi:trash-can" size="13" class="text-red-400" />
              </button>
            </div>
          </div>
        </div>

        <div v-if="educationData.length === 0" class="flex flex-col items-center justify-center py-12 text-center">
          <Icon name="mdi:school-outline" size="32" class="text-gray-200 mb-2" />
          <p class="text-xs text-gray-400">No education added yet</p>
        </div>
      </div>

      <!-- Experience -->
      <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
          <div class="flex items-center gap-2">
            <span class="text-lg">💼</span>
            <h3 class="font-black text-gray-900">Experience</h3>
          </div>
          <button @click="openModal('experience')"
            class="inline-flex items-center gap-1.5 bg-black text-white text-[10px] font-black uppercase tracking-widest px-3 py-2 rounded-full hover:bg-gray-800 transition-colors">
            <Icon name="mdi:plus" size="12" />
            Add
          </button>
        </div>

        <div class="divide-y divide-gray-50">
          <div v-for="item in experienceData" :key="item.id"
            class="flex items-start justify-between gap-4 px-6 py-4 hover:bg-gray-50 transition-colors group">
            <div class="flex items-start gap-3">
              <span class="mt-1.5 w-2 h-2 rounded-full bg-blue-400 shrink-0"></span>
              <div>
                <p class="text-sm font-semibold text-gray-800 leading-snug">{{ item.title }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ item.date }}</p>
              </div>
            </div>
            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity shrink-0">
              <button @click="editItem('experience', item)"
                class="p-1.5 hover:bg-gray-100 rounded-lg transition-colors">
                <Icon name="mdi:pencil" size="13" class="text-gray-400" />
              </button>
              <button @click="deleteItem('experience', item.id)"
                class="p-1.5 hover:bg-red-50 rounded-lg transition-colors">
                <Icon name="mdi:trash-can" size="13" class="text-red-400" />
              </button>
            </div>
          </div>
        </div>

        <div v-if="experienceData.length === 0" class="flex flex-col items-center justify-center py-12 text-center">
          <Icon name="mdi:briefcase-outline" size="32" class="text-gray-200 mb-2" />
          <p class="text-xs text-gray-400">No experience added yet</p>
        </div>
      </div>

    </div>

    <!-- Modal -->
    <div v-if="modal.open"
      class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center px-4">
      <div class="bg-white rounded-2xl border border-gray-100 shadow-2xl w-full max-w-md">

        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
          <h3 class="font-black text-gray-900 capitalize">
            {{ modal.editing ? 'Edit' : 'Add' }} {{ modal.type === 'education' ? '🎓 Education' : '💼 Experience' }}
          </h3>
          <button @click="closeModal" class="p-1.5 hover:bg-gray-100 rounded-lg transition-colors">
            <Icon name="mdi:close" size="16" class="text-gray-500" />
          </button>
        </div>

        <div class="p-6 flex flex-col gap-4">
          <div>
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1.5">
              {{ modal.type === 'education' ? 'School / Degree' : 'Position / Company' }}
            </label>
            <input v-model="form.title" type="text"
              :placeholder="modal.type === 'education' ? 'e.g. Bachelor of Computer Science...' : 'e.g. Software Engineer - Company'"
              class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-black transition-colors" />
          </div>
          <div>
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1.5">
              {{ modal.type === 'education' ? 'Year' : 'Date Range' }}
            </label>
            <input v-model="form.date" type="text"
              :placeholder="modal.type === 'education' ? 'e.g. ២០២២' : 'e.g. មករា ២០២៥ - បច្ចុប្បន្ន'"
              class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-black transition-colors" />
          </div>
        </div>

        <div class="flex gap-3 px-6 pb-6">
          <button @click="closeModal"
            class="flex-1 border border-gray-200 text-gray-600 text-sm font-bold py-2.5 rounded-full hover:border-gray-400 transition-colors">
            Cancel
          </button>
          <button @click="saveItem"
            class="flex-1 bg-black text-white text-sm font-bold py-2.5 rounded-full hover:bg-gray-800 transition-colors">
            {{ modal.editing ? 'Save Changes' : 'Add' }}
          </button>
        </div>

      </div>
    </div>

  </div>
</template>

<script setup>
definePageMeta({ layout: 'admin-layout' })

const client = useSanctumClient()
const config = useRuntimeConfig()
const baseUrl = config.public.apiBase

/* ===============================
   FETCH DATA
=================================*/
const { data: eduRes, refresh: refreshEdu } = await useFetch(
  `${baseUrl}/api/education`,
  { default: () => [] }
)

const { data: expRes, refresh: refreshExp } = await useFetch(
  `${baseUrl}/api/experience`,
  { default: () => [] }
)

const educationData = computed(() => eduRes.value ?? [])
const experienceData = computed(() => expRes.value ?? [])

/* ===============================
   MODAL STATE
=================================*/
const modal = ref({ open: false, type: 'education', editing: false, editId: null })
const form = ref({ title: '', date: '' })

function openModal(type) {
  modal.value = { open: true, type, editing: false, editId: null }
  form.value = { title: '', date: '' }
}

function editItem(type, item) {
  modal.value = { open: true, type, editing: true, editId: item.id }
  form.value = { title: item.title, date: item.date }
}

function closeModal() {
  modal.value.open = false
  form.value = { title: '', date: '' }
}

/* ===============================
   SAVE (CREATE / UPDATE)
=================================*/
async function saveItem() {
  try {
    const endpoint = modal.value.type === 'education'
      ? `${baseUrl}/api/education`
      : `${baseUrl}/api/experience`

    if (modal.value.editing) {
      await client(`${endpoint}/${modal.value.editId}`, {
        method: 'PUT',
        body: form.value,
      })
    } else {
      await client(endpoint, {
        method: 'POST',
        body: form.value,
      })
    }

    modal.value.type === 'education' ? await refreshEdu() : await refreshExp()
    closeModal()

  } catch (error) {
    console.error('Save error:', error)
    alert('Failed to save. Check the console for details.')
  }
}

/* ===============================
   DELETE
=================================*/
async function deleteItem(type, id) {
  if (!confirm('Delete this item?')) return

  try {
    const endpoint = type === 'education'
      ? `${baseUrl}/api/education/${id}`
      : `${baseUrl}/api/experience/${id}`

    await client(endpoint, { method: 'DELETE' })

    type === 'education' ? await refreshEdu() : await refreshExp()

  } catch (error) {
    console.error('Delete error:', error)
    alert('Failed to delete.')
  }
}
</script>