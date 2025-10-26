<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { CalendarDays, Calendar1 } from 'lucide-vue-next';

interface Goal {
    id: number
    title: string
    description?: string
    start_date?: string
    end_date?: string
    status?: string
}

const weeklyGoals = ref<Goal[]>([])
const monthlyGoals = ref<Goal[]>([])
const loading = ref(true)
const error = ref('')

// Fetch weekly and monthly goals
const fetchGoals = async () => {
    loading.value = true
    error.value = ''
    try {
        const [weeklyRes, monthlyRes] = await Promise.all([
            axios.get('/api/goals/weekly'),
            axios.get('/api/goals/monthly')
        ])
        weeklyGoals.value = weeklyRes.data.data
        monthlyGoals.value = monthlyRes.data.data
    } catch (err) {
        console.error(err)
        error.value = 'Failed to load goals.'
    } finally {
        loading.value = false
    }
}

onMounted(fetchGoals)
</script>

<template>
    <div class="flex flex-col h-screen p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">My Goals</h2>

        <div v-if="loading" class="text-gray-500">Loading goals...</div>
        <div v-if="error" class="text-red-500">{{ error }}</div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4 flex-1">
            <!-- Weekly Goals -->
            <div class="max-h-40 overflow-y-auto bg-yellow-100 p-4 rounded shadow-inner flex flex-col">
                <h3 class="flex items-center font-bold text-lg mb-2">
                    <Calendar1 class="w-5 h-5 mr-1" />
                    Weekly Goals
                </h3>
                <ul class="space-y-2 flex-1">
                    <li
                        v-for="goal in weeklyGoals"
                        :key="goal.id"
                        class="bg-yellow-200 p-3 rounded shadow-md"
                    >
                        <h4>{{ goal.title }}</h4>
                    </li>
                    <li v-if="weeklyGoals.length === 0" class="text-gray-500">No weekly goals.</li>
                </ul>
            </div>

            <!-- Monthly Goals -->
            <div class="max-h-40 overflow-y-auto bg-blue-100 p-4 rounded shadow-inner flex flex-col">
                <h3 class="flex items-center font-bold text-lg mb-2">
                    <CalendarDays class="w-5 h-5 mr-1" />
                    Monthly Goals
                </h3>
                <ul class="space-y-2 flex-1">
                    <li
                        v-for="goal in monthlyGoals"
                        :key="goal.id"
                        class="bg-blue-200 p-3 rounded shadow-md"
                    >
                        <h4>{{ goal.title }}</h4>
                    </li>
                    <li v-if="monthlyGoals.length === 0" class="text-gray-500">No monthly goals.</li>
                </ul>
            </div>
        </div>
    </div>
</template>

<style scoped>
li {
    transition: transform 0.2s;
}
li:hover {
    transform: translateY(-2px) scale(1.02);
}
</style>
