<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { Gift } from 'lucide-vue-next';

interface Habit {
    id: number
    name: string
    reward?: string
}

const habits = ref<Habit[]>([])
const loading = ref(false)
const error = ref('')

const fetchRewards = async () => {
    loading.value = true
    error.value = ''
    try {
        const { data } = await axios.get('/api/habits/active')
        habits.value = data.data
    } catch (e) {
        console.error(e)
        error.value = 'Failed to load rewards.'
    } finally {
        loading.value = false
    }
}

onMounted(fetchRewards)
</script>

<template>
    <div class="flex flex-col h-full p-6 bg-white rounded shadow-md">
        <h2 class="text-xl font-bold mb-4">Go for the rewards!</h2>

        <div v-if="loading" class="text-gray-500">Loading...</div>
        <div v-if="error" class="text-red-500">{{ error }}</div>

        <ul v-if="!loading && habits.length" class="space-y-2 max-h-60 overflow-y-auto">
            <li
                v-for="habit in habits"
                :key="habit.id"
                class="flex items-center p-3 border rounded bg-indigo-100"
            >
                <span class="text-blue-600 mr-2">
                      <Gift />
                </span>
                <div>
                    <div class="font-semi bold">{{ habit.name }}</div>
                    <div class="text-sm text-gray-700">{{ habit.reward || 'No reward set' }}</div>
                </div>
            </li>
        </ul>

        <div v-else-if="!loading" class="text-gray-500">
            No rewards found.
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
