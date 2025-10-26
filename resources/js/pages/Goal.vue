<script setup lang="ts">
import { ref, onMounted, watch, reactive } from 'vue';
import axios from "axios"
import AppLayout from '@/layouts/AppLayout.vue';
import { goals } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Goals',
        href: goals().url,
    },
];

interface Goal {
    id: number
    title: string
    description: string
    type: string
    start_date: string
    end_date: string
    status: string
}

interface GoalForm {
    title: string
    description: string
    type: string
    start_date: string
    end_date: string
    status: string
}

interface ApiResponse<T> {
    data: T
    status: string
    message: string
}

const goalItems = ref<Goal[]>([])

const form = ref<GoalForm>({
    title: "",
    description: "",
    start_date: "",
    end_date: "",
    status: "active",
    type: "annual"
})

const editingId = ref<number | null>(null)

const API_URL = "/api/goals"

const message = ref<string>("")

const messageType = ref<"success" | "error">("success")

const fetchGoals = async () => {
    const { data } = await axios.get<ApiResponse<Goal[]>>(API_URL)
    goalItems.value = data.data
}

const saveGoal = async () => {
    try {
        if (editingId.value) {
            await axios.put(`${API_URL}/${editingId.value}`, form.value)

            message.value = "The goal was successfully updated"
            messageType.value = "success"
        } else {
            await axios.post(API_URL, form.value)

            message.value = "The goal was successfully created"
            messageType.value = "success"
        }

        setTimeout(() => {
            message.value = ""
        }, 3000)

        await fetchGoals()
        resetForm()
    } catch (error) {
        console.error("Error saving goal:", error)

        message.value = "An error occurred while saving the goal"
        messageType.value = "error"

        setTimeout(() => {
            message.value = ""
        }, 3000)
    }
}

const editGoal = (goal: Goal) => {
    editingId.value = goal.id

    form.value = {
        title: goal.title,
        description: goal.description,
        start_date: goal.start_date,
        end_date: goal.end_date,
        status: goal.status,
        type: goal.type
    }
}

const capitalize = (text: string) => {
    if (!text) return ''
    return text.charAt(0).toUpperCase() + text.slice(1)
}

const getStatusBadgeClass = (status: string) => {
    switch (status) {
        case 'active':
            return 'bg-blue-100 text-blue-800 border border-blue-300'
        case 'completed':
            return 'bg-green-100 text-green-800 border border-green-300'
        case 'archived':
            return 'bg-gray-200 text-gray-700 border border-gray-300'
        default:
            return 'bg-gray-100 text-gray-800 border border-gray-200'
    }
}

const errors = reactive({
    title: '',
    description: '',
    date: '',
    type: '',
    status: '',
})

const validateDates = () => {
    if (form.value.start_date && form.value.end_date && form.value.start_date > form.value.end_date) {
        errors.date = 'The start date cannot be later than the end date'
    } else {
        errors.date = ''
    }
}

watch(() => [form.value.start_date, form.value.end_date], validateDates)

const deleteGoal = async (id: number) => {
    const confirmed = confirm("Are you sure you want to delete this goal? This action cannot be undone.")
    if (!confirmed) return

    try {
        await axios.delete(`${API_URL}/${id}`)
        await fetchGoals()

        message.value = "The goal was successfully deleted"
        messageType.value = "success"

        setTimeout(() => (message.value = ""), 3000)
    } catch (error) {
        console.error("Error deleting goal:", error)

        message.value = "Something went wrong while deleting the goal"
        messageType.value = "error"
        setTimeout(() => (message.value = ""), 3000)
    }
}

const cancelEdit = () => resetForm()

const resetForm = () => {
    form.value = {
        title: "",
        description: "",
        start_date: "",
        end_date: "",
        status: "active",
        type: "annual"
    }
    editingId.value = null
}

onMounted(fetchGoals)
</script>

<template>
    <Head title="Goals" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-4">
                    {{ editingId ? "Edit goal" : "Create a new goal!" }}
                </h2>

                <!-- Form -->
                <form @submit.prevent="saveGoal" class="space-y-3 mb-6">
                    <!-- Title -->
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input
                        v-model="form.title"
                        type="text"
                        placeholder="Goal title"
                        class="w-full border rounded p-2"
                        :required="!editingId"
                    />

                    <!-- Description -->
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea
                        v-model="form.description"
                        placeholder="Goal description"
                        class="w-full border rounded p-2"
                    ></textarea>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input
                                v-model="form.start_date"
                                type="date"
                                class="w-full border rounded p-2"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input
                                v-model="form.end_date"
                                type="date"
                                class="w-full border rounded p-2"
                            />
                        </div>
                        <p v-if="errors.date" class="text-red-500 text-sm mt-1">{{ errors.date }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <!-- Type -->
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select
                                v-model="form.type"
                                class="w-full border rounded p-2"
                            >
                                <option value="annual">Annual</option>
                                <option value="monthly">Monthly</option>
                                <option value="weekly">Weekly</option>
                            </select>
                        </div>
                        <div>
                            <!-- Status -->
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select
                                v-model="form.status"
                                class="w-full border rounded p-2"
                            >
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                    </div>

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
                <h1 class="text-2xl font-bold mb-4">My Goals</h1>
                <table class="w-full border-collapse border">
                    <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2 text-left">Title</th>
                        <th class="border p-2 text-left">Description</th>
                        <th class="border p-2 text-left">Start Date</th>
                        <th class="border p-2 text-left">End Date</th>
                        <th class="border p-2 text-left">Type</th>
                        <th class="border p-2 text-left">Status</th>
                        <th class="border p-2 text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="goal in goalItems" :key="goal.id" class="hover:bg-gray-50">
                        <td class="border p-2">{{ goal.title }}</td>
                        <td class="border p-2">{{ goal.description }}</td>
                        <td class="border p-2">{{ goal.start_date }}</td>
                        <td class="border p-2">{{ goal.end_date }}</td>
                        <td class="border p-2">{{ capitalize(goal.type) }}</td>
                        <td class="border p-2">
                            <span
                                class="px-2 py-1 rounded-full text-sm font-semi bold"
                                :class="getStatusBadgeClass(goal.status)"
                            >
                                {{ capitalize(goal.status) }}
                            </span>
                        </td>
                        <td class="border p-2 text-center">
                            <div class="flex justify-center space-x-2 flex-nowrap">
                                <button
                                    @click="editGoal(goal)"
                                    class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600 whitespace-nowrap"
                                >
                                    Edit
                                </button>
                                <button
                                    @click="deleteGoal(goal.id)"
                                    class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 whitespace-nowrap"
                                >
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="goals.length === 0">
                        <td colspan="3" class="text-center p-4 text-gray-500">No goals found</td>
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
