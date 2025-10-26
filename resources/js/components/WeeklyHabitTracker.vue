<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

interface Goal {
    id: number
    title: string
}

interface HabitLog {
    id?: number
    habit_id: number
    date: string
    completed: boolean
}

interface Habit {
    id: number
    goal_id: number
    name: string
    status: string
    goal?: Goal
    logs: HabitLog[]
}

const habits = ref<Habit[]>([])
const loading = ref(false)
const successMessage = ref("")

const fetchHabits = async () => {
    loading.value = true
    try {
        const { data } = await axios.get('/api/habits/active')
        habits.value = data.data
    } catch (error) {
        console.error("Error fetching habits:", error)
    } finally {
        loading.value = false
    }
}

const getGoalTitle = (habit: Habit) => habit.goal?.title ?? '—'

const daysOfWeek = [
    { key: 'monday', label: 'Mon' },
    { key: 'tuesday', label: 'Tue' },
    { key: 'wednesday', label: 'Wed' },
    { key: 'thursday', label: 'Thu' },
    { key: 'friday', label: 'Fri' },
    { key: 'saturday', label: 'Sat' },
    { key: 'sunday', label: 'Sun' },
]

// Returns a Date object for the specified weekday of the current week
const getDateOfWeekday = (weekdayKey: string): Date => {
    const now = new Date()

    // Calculate Monday of the current week (Sunday as last day)
    const day = now.getDay() || 7
    const monday = new Date(now)
    monday.setDate(now.getDate() - day + 1)
    monday.setHours(0, 0, 0, 0)

    // Find index of the requested weekday
    const dayIndex = daysOfWeek.findIndex(d => d.key === weekdayKey)
    const date = new Date(monday)
    date.setDate(monday.getDate() + dayIndex)

    return date
}

// Formats the weekday date as DD/MM
const formatDayDate = (dayKey: string) => {
    const d = getDateOfWeekday(dayKey)
    const day = String(d.getDate()).padStart(2, '0')
    const month = String(d.getMonth() + 1).padStart(2, '0')

    return `${day}/${month}`
}

// Retrieves the habit log for a specific day
const getLogForDay = (habit: Habit, dayKey: string) => {
    const date = getDateOfWeekday(dayKey)
    const isoDate = date.toISOString().slice(0, 10)

    return habit.logs.find(log => log.date === isoDate)
}

const isDayChecked = (habit: Habit, dayKey: string) => {
    const log = getLogForDay(habit, dayKey)
    return log?.completed || false
}

// Toggles the status of a habit log for the given day
const toggleLog = async (habit: Habit, weekdayKey: string) => {
    const dateObj = getDateOfWeekday(weekdayKey)
    const date = dateObj.toISOString().slice(0, 10)

    const existing = habit.logs.find(log => log.date === date)
    const newStatus = existing ? !existing.completed : true

    try {
        if (existing) {
            // Update existing log
            await axios.put(`/api/habit-logs/${existing.id}`, {
                completed: newStatus,
            })

            existing.completed = newStatus
        } else {
            // Create new log
            const { data } = await axios.post('/api/habit-logs', {
                habit_id: habit.id,
                date,
                completed: newStatus,
            })
            habit.logs.push(data.data)
        }

        successMessage.value = `Progress saved for ${habit.name}`
        setTimeout(() => (successMessage.value = ""), 3000)
    } catch (error) {
        console.error("Error saving log:", error)
    }
}

onMounted(fetchHabits)
</script>

<template>
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-4">Weekly Habit Tracker</h2>

        <div
            v-if="successMessage"
            class="bg-green-100 border border-green-300 text-green-800 p-3 rounded mb-4 flex justify-between items-center"
        >
            <span>{{ successMessage }}</span>
            <button @click="successMessage = ''" class="font-bold text-lg">&times;</button>
        </div>

        <div v-if="loading" class="text-gray-500">Loading habits...</div>

        <table v-else class="w-full border-collapse border">
            <thead>
            <tr class="bg-gray-100">
                <th class="border p-2 text-left">Habit</th>
                <th class="border p-2 text-left">Goal</th>
                <th
                    v-for="day in daysOfWeek"
                    :key="day.key"
                    class="border p-2 text-center"
                >
                    <div class="flex flex-col items-center">
                        <span class="font-medium">{{ day.label }}</span>
                        <span class="text-xs text-gray-500">{{ formatDayDate(day.key) }}</span>
                    </div>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="habit in habits"
                :key="habit.id"
                class="hover:bg-gray-50"
            >
                <td class="border p-2 font-medium">{{ habit.name }}</td>
                <td class="border p-2 text-gray-600">
                    {{ getGoalTitle(habit) }}
                </td>
                <td
                    v-for="day in daysOfWeek"
                    :key="day.key"
                    class="border p-2 text-center"
                >
                    <input
                        type="checkbox"
                        :checked="isDayChecked(habit, day.key)"
                        @change="toggleLog(habit, day.key)"
                    />
                </td>
            </tr>
            <tr v-if="habits.length === 0">
                <td colspan="9" class="text-center text-gray-500 p-4">
                    No active habits found.
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

