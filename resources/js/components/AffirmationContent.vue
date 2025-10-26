<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

interface Affirmation {
    id: number
    text: string
    created_at: string
}

const affirmations = ref<Affirmation[]>([])
const loading = ref(false)
const error = ref('')

// Fetch the last 3 affirmations for the current user
const fetchAffirmations = async () => {
    loading.value = true
    error.value = ''
    try {
        const { data } = await axios.get('/api/affirmations')
        affirmations.value = data.data.slice(0, 3)
    } catch (err) {
        console.error(err)
        error.value = 'Failed to load affirmations.'
    } finally {
        loading.value = false
    }
}

onMounted(fetchAffirmations)
</script>

<template>
    <div class="flex flex-col h-full p-6 bg-white rounded shadow-md">
        <h2 class="text-xl font-bold mb-4">My Affirmations</h2>

        <div v-if="loading" class="text-gray-500">Loading...</div>
        <div v-if="error" class="text-red-500">{{ error }}</div>

        <ul
            v-if="!loading && affirmations.length"
            class="space-y-2 max-h-50 overflow-y-auto"
        >
            <li
                v-for="affirmation in affirmations"
                :key="affirmation.id"
                class="flex items-center p-2 border rounded bg-indigo-100"
            >
                <span class="text-green-500 mr-2">✔</span>
                {{ affirmation.text }}
            </li>
        </ul>

        <div v-else-if="!loading" class="text-gray-500">
            No affirmations found.
        </div>
    </div>
</template>

<style scoped>
li {
    transition: transform 0.2s;
}
li:hover {
    transform: translateY(-2px) scale(1);
}
</style>
