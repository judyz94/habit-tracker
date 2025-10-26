<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from "axios"
import AppLayout from '@/layouts/AppLayout.vue';
import { habits } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Habits',
        href: habits().url,
    },
];

interface Habit {
    id: number
    goal_id: number
    name: string
    description: string
    schedule_time: string
    repeat_days: string[]
    min_action: string
    min_time: number
    environment_design?: string
    reward?: string
    notes?: string
    status: string
}

interface Goal {
    id: number
    title: string
    description: string
}

interface HabitForm {
    goal_id: number
    name: string
    description: string
    schedule_time: string
    repeat_days: string[]
    min_action: string
    min_time: number
    environment_design?: string
    reward?: string
    notes?: string
    status: string
}

interface ApiResponse<T> {
    data: T
    status: string
    message: string
}

const habitItems = ref<Habit[]>([])

const goals = ref<Goal[]>([])

const message = ref<string>("")

const messageType = ref<"success" | "error">("success")

const form = ref<HabitForm>({
    goal_id: 0,
    name: "",
    description: "",
    schedule_time: "07:00",
    repeat_days: [] as string[],
    min_action: "",
    min_time: 15,
    environment_design: "",
    reward: "",
    notes: "",
    status: "active",
})

const editingId = ref<number | null>(null)

const API_URL = "/api/habits"

const fetchHabits = async () => {
    const { data } = await axios.get<ApiResponse<Habit[]>>(API_URL)
    habitItems.value = data.data
}

const fetchGoals = async () => {
    try {
        const { data } = await axios.get("/api/goals")
        goals.value = data.data
    } catch (error) {
        console.error("Error loading goals:", error)
    }
}


const saveHabit = async () => {
    try {
        if (form.value.schedule_time) {
            form.value.schedule_time = form.value.schedule_time.slice(0, 5)
        }

        if (editingId.value) {
            await axios.put(`${API_URL}/${editingId.value}`, form.value)

            message.value = "The habit was successfully updated"
            messageType.value = "success"
        } else {
            await axios.post(API_URL, form.value)

            message.value = "The habit was successfully created"
            messageType.value = "success"
        }

        setTimeout(() => {
            message.value = ""
        }, 3000)

        await fetchHabits()
        resetForm()
    } catch (error) {
        console.error("Error saving habit:", error)

        message.value = "An error occurred while saving the habit"
        messageType.value = "error"

        setTimeout(() => {
            message.value = ""
        }, 3000)
    }
}

const editHabit = (habit: Habit) => {
    editingId.value = habit.id

    form.value = {
        goal_id: habit.goal_id,
        name: habit.name,
        description: habit.description,
        schedule_time: habit.schedule_time,
        repeat_days: habit.repeat_days || [],
        min_action: habit.min_action,
        min_time: habit.min_time,
        environment_design: habit.environment_design,
        reward: habit.reward,
        notes: habit.notes,
        status: habit.status,
    }
}

const weekDays = [
    'Monday',
    'Tuesday',
    'Wednesday',
    'Thursday',
    'Friday',
    'Saturday',
    'Sunday'
]

const capitalize = (text: string) => {
    if (!text) return ''
    return text.charAt(0).toUpperCase() + text.slice(1)
}

const getStatusBadgeClass = (status: string) => {
    switch (status) {
        case 'active':
            return 'bg-blue-100 text-blue-800 border border-blue-300'
        case 'paused':
            return 'bg-green-100 text-green-800 border border-green-300'
        case 'completed':
            return 'bg-gray-200 text-gray-700 border border-gray-300'
        default:
            return 'bg-gray-100 text-gray-800 border border-gray-200'
    }
}

const deleteHabit = async (id: number) => {
    const confirmed = confirm("Are you sure you want to delete this habit? This action cannot be undone.")
    if (!confirmed) return

    try {
        await axios.delete(`${API_URL}/${id}`)
        await fetchHabits()

        message.value = "The habit was successfully deleted"
        messageType.value = "success"

        setTimeout(() => (message.value = ""), 3000)
    } catch (error) {
        console.error("Error deleting habit:", error)

        message.value = "Something went wrong while deleting the habit"
        messageType.value = "error"
        setTimeout(() => (message.value = ""), 3000)
    }
}

const cancelEdit = () => resetForm()

const resetForm = () => {
    form.value = {
        goal_id: 0,
        name: "",
        description: "",
        schedule_time: "07:00",
        repeat_days: [],
        min_action: "",
        min_time: 15,
        environment_design: "",
        reward: "",
        notes: "",
        status: "active",
    }
    editingId.value = null
}

onMounted(() => {
    fetchHabits()
    fetchGoals()
})
</script>

<template>
    <Head title="Habits" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-4">
                    {{ editingId ? "Edit habit" : "Create a new habit!" }}
                </h2>

                <!-- Form -->
                <form @submit.prevent="saveHabit" class="space-y-3 mb-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <!-- Name -->
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input
                                v-model="form.name"
                                type="text"
                                placeholder="Habit name"
                                class="w-full border rounded p-2"
                                :required="!editingId"
                            />
                        </div>
                        <div>
                            <!-- Goal -->
                            <label class="block text-sm font-medium text-gray-700 mb-1">Goal</label>
                            <select
                                v-model="form.goal_id"
                                class="w-full border rounded p-2"
                                :required="!editingId"
                            >
                                <option value="" disabled>Select a goal</option>
                                <option
                                    v-for="goal in goals"
                                    :key="goal.id"
                                    :value="goal.id"
                                >
                                    {{ goal.title }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Description -->
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea
                        v-model="form.description"
                        placeholder="Describe the habit"
                        class="w-full border rounded p-2"
                    ></textarea>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <!-- Schedule Time -->
                            <label class="block text-sm font-medium text-gray-700 mb-1">Schedule Time</label>
                            <input
                                v-model="form.schedule_time"
                                type="time"
                                class="w-full border rounded p-2"
                                :required="!editingId"
                            />
                        </div>
                        <div>
                            <!-- Minimum Time -->
                            <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Time (minutes)</label>
                            <input
                                v-model.number="form.min_time"
                                type="number"
                                min="1"
                                class="w-full border rounded p-2"
                                placeholder="e.g. 15"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <!-- Reward -->
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reward</label>
                            <input
                                v-model="form.reward"
                                type="text"
                                placeholder="What will you do after completing it?"
                                class="w-full border rounded p-2"
                            />
                        </div>
                        <div>
                            <!-- Minimum Action -->
                            <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Action</label>
                            <input
                                v-model="form.min_action"
                                type="text"
                                placeholder="Smallest possible version of the habit"
                                class="w-full border rounded p-2"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <!-- Repeat Days -->
                            <label class="block text-sm font-medium text-gray-700 mb-1">Repeat Days</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                <label
                                    v-for="day in weekDays"
                                    :key="day"
                                    class="flex items-center space-x-2 border rounded p-2 cursor-pointer hover:bg-gray-100"
                                >
                                    <input
                                        type="checkbox"
                                        :value="day"
                                        v-model="form.repeat_days"
                                        class="accent-blue-600"
                                    />
                                    <span class="capitalize">{{ day.toLowerCase() }}</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <!-- Status -->
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select v-model="form.status" class="w-full border rounded p-2">
                                <option value="active">Active</option>
                                <option value="paused">Paused</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>

                    <!-- Environment Design -->
                    <label class="block text-sm font-medium text-gray-700 mb-1">Environment Design</label>
                    <textarea
                        v-model="form.environment_design"
                        placeholder="Describe how you'll make this habit easier to do"
                        class="w-full border rounded p-2"
                    ></textarea>

                    <!-- Notes -->
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea
                        v-model="form.notes"
                        placeholder="Any observations or adjustments"
                        class="w-full border rounded p-2"
                    ></textarea>


                    <!-- Buttons -->
                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700"
                    >
                        {{ editingId ? "Update" : "Create" }}
                    </button>

                    <button
                        v-if="editingId"
                        type="button"
                        @click="cancelEdit"
                        class="bg-gray-400 text-white px-3 py-1 rounded hover:bg-gray-500 ml-2"
                    >
                        Cancel
                    </button>

                    <div
                        v-if="message"
                        class="flex items-center justify-between mt-2 p-2 rounded-lg border shadow-sm mb-4"
                        :class="messageType === 'success'
                        ? 'bg-green-100 border-green-300 text-green-800'
                        : 'bg-red-100 border-red-300 text-red-800'"
                        role="alert"
                    >
                        <span>{{ message }}</span>
                        <button
                            type="button"
                            class="text-lg font-bold hover:opacity-70"
                            @click="message = ''"
                        >
                            ×
                        </button>
                    </div>
                </form>

                <hr class="my-8 border-t border-gray-300 dark:border-gray-700" />

                <!-- Table -->
                <h1 class="text-2xl font-bold mb-4">My Habits</h1>
                <table class="w-full border-collapse border">
                    <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2 text-left">Name</th>
                        <th class="border p-2 text-left">Schedule</th>
                        <th class="border p-2 text-left">Repeat Days</th>
                        <th class="border p-2 text-left">Min Action</th>
                        <th class="border p-2 text-left">Min Time</th>
                        <th class="border p-2 text-left">Status</th>
                        <th class="border p-2 text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="habit in habitItems" :key="habit.id" class="hover:bg-gray-50">
                        <td class="border p-2">{{ habit.name }}</td>
                        <td class="border p-2">{{ habit.schedule_time.substring(0, 5) }}</td>
                        <td class="border p-2">
                            {{ habit.repeat_days && habit.repeat_days.length ? habit.repeat_days.map(capitalize).join(', ') : '-' }}
                        </td>
                        <td class="border p-2">{{ habit.min_action }}</td>
                        <td class="border p-2">{{ habit.min_time }} min</td>
                        <td class="border p-2">
                            <span
                                class="px-2 py-1 rounded-full text-sm font-semi bold"
                                :class="getStatusBadgeClass(habit.status)"
                            >
                                {{ capitalize(habit.status) }}
                            </span>
                        </td>
                        <td class="border p-2 text-center">
                            <div class="flex justify-center space-x-2 flex-nowrap">
                                <button
                                    @click="editHabit(habit)"
                                    class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600 whitespace-nowrap"
                                >
                                    Edit
                                </button>
                                <button
                                    @click="deleteHabit(habit.id)"
                                    class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 whitespace-nowrap"
                                >
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="habits.length === 0">
                        <td colspan="3" class="text-center p-4 text-gray-500">No habits found</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
table {
    border: 1px solid #ddd;
}
</style>
