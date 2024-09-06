<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-base-300 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <!-- Search and Filter Section -->
                        <div class="mb-6">
                            <div class="flex flex-col sm:flex-row gap-4">
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search events..."
                                    class="input input-bordered w-full sm:w-1/2"
                                    @input="filterEvents"
                                />
                                <select
                                    v-model="selectedStatus"
                                    class="select select-bordered w-full sm:w-1/4"
                                    @change="filterEvents"
                                >
                                    <option value="">All Statuses</option>
                                    <option
                                        v-for="status in Object.values(EventStatus)"
                                        :key="status"
                                        :value="status"
                                    >
                                        {{ formatStatus(status) }}
                                    </option>
                                </select>
                                <select
                                    v-model="selectedMonth"
                                    class="select select-bordered w-full sm:w-1/4"
                                    @change="filterEvents"
                                >
                                    <option value="">All Months</option>
                                    <option v-for="month in months" :key="month" :value="month">
                                        {{ month }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Events List -->
                        <div v-if="filteredEvents.length > 0" class="space-y-4">
                            <div
                                v-for="event in filteredEvents"
                                :key="event.id"
                                class="bg-base-200 rounded-lg shadow-md p-4"
                            >
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-semibold text-primary">
                                        {{ event.title }}
                                    </h3>
                                    <span class="text-sm text-base-content opacity-70">
                                        <i class="fas fa-calendar-day mr-1"></i
                                        >{{ formatDate(event.start_date) }}
                                    </span>
                                </div>
                                <p class="text-sm text-base-content opacity-70 mb-2">
                                    {{ event.description }}
                                </p>
                                <div class="flex justify-between items-center">
                                    <StatusBadge :status="event.status" />
                                    <div class="space-x-2">
                                        <button
                                            class="btn btn-sm btn-primary"
                                            @click="viewEvent(event)"
                                        >
                                            View Event
                                        </button>
                                        <button
                                            class="btn btn-sm btn-secondary"
                                            :disabled="!canJoinEvent(event)"
                                            @click="applyForEvent(event)"
                                        >
                                            Apply
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-8 text-base-content opacity-70">
                            <i class="fas fa-exclamation-circle mr-2"></i> No events found
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <SnackbarNotification ref="snackbar" />
    </AuthenticatedLayout>
</template>

<script>
import { route } from 'ziggy-js'
import { router } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import AuthenticatedLayout from '@/components/layouts/AuthenticatedLayout.vue'
import SnackbarNotification from '@/components/ui/SnackbarNotification.vue'
import StatusBadge from '@/components/common/StatusBadge.vue'
import { EventStatus } from '@/enums/eventStatus'
import axios from 'axios'

export default {
    name: 'EventsListing',
    components: {
        AuthenticatedLayout,
        SnackbarNotification,
        StatusBadge
    },
    setup() {
        const allEvents = ref([])
        const searchQuery = ref('')
        const selectedStatus = ref('')
        const selectedMonth = ref('')
        const filteredEvents = ref([])
        const snackbar = ref(null)
        const isLoading = ref(true)
        const error = ref(null)

        const months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ]

        const formatDate = (dateString) => {
            return new Date(dateString).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            })
        }

        const formatStatus = (status) => {
            return status
                .replace('_', ' ')
                .toLowerCase()
                .replace(/\b\w/g, (l) => l.toUpperCase())
        }

        const fetchEvents = async () => {
            try {
                isLoading.value = true
                const response = await fetch(route('events.list'))
                if (!response.ok) throw new Error('Failed to fetch events')
                allEvents.value = await response.json()
                filterEvents()
            } catch (e) {
                error.value = 'Failed to load events. Please try again later.'
                snackbar.value.show(error.value, 'error')
            } finally {
                isLoading.value = false
            }
        }

        const filterEvents = () => {
            filteredEvents.value = allEvents.value.filter((event) => {
                const matchesSearch =
                    event.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                    event.description.toLowerCase().includes(searchQuery.value.toLowerCase())
                const matchesStatus = !selectedStatus.value || event.status === selectedStatus.value
                const matchesMonth =
                    !selectedMonth.value ||
                    new Date(event.start_date).toLocaleString('default', { month: 'long' }) ===
                        selectedMonth.value
                return matchesSearch && matchesStatus && matchesMonth
            })
        }

        const viewEvent = (event) => {
            router.visit(route('event.index', { id: event.id }))
        }

        const canJoinEvent = (event) => {
            return event.status !== EventStatus.COMPLETED && event.status !== EventStatus.CANCELLED
        }

        const applyForEvent = async (event) => {
            if (event.status === EventStatus.CANCELLED) {
                snackbar.value.show('This event has been cancelled', 'error')
                return
            } else if (event.status === EventStatus.COMPLETED) {
                snackbar.value.show('This event has already ended', 'error')
                return
            }

            isLoading.value = true
            try {
                const response = await axios.post(route('event.join'), { event_id: event.id })
                snackbar.value.show(response.data.message, 'success')
                fetchEvents()
            } catch (e) {
                snackbar.value.show(e.response.data.message || 'Failed to apply for event', 'error')
            } finally {
                isLoading.value = false
            }
        }

        onMounted(fetchEvents)

        return {
            EventStatus,
            filteredEvents,
            searchQuery,
            selectedStatus,
            selectedMonth,
            months,
            snackbar,
            isLoading,
            error,
            formatDate,
            formatStatus,
            filterEvents,
            viewEvent,
            canJoinEvent,
            applyForEvent
        }
    }
}
</script>
