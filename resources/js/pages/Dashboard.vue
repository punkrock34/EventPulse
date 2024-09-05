<template>
    <AuthenticatedLayout>
        <div class="p-6">
            <h1 class="text-3xl font-bold mb-6">Dashboard</h1>
            <p class="text-lg mb-8">Welcome back, {{ user.name }}!</p>

            <!-- Quick Stats -->
            <div class="mb-12 grid gap-6 md:grid-cols-3">
                <div class="stat bg-base-200 rounded-lg shadow p-4">
                    <div class="stat-figure text-primary">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                    <div class="stat-title">Total Events</div>
                    <div class="stat-value">{{ props.events.length }}</div>
                </div>
                <div class="stat bg-base-200 rounded-lg shadow p-4">
                    <div class="stat-figure text-secondary">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div class="stat-title">Total Attendees</div>
                    <div class="stat-value">{{ getTotalAttendees() }}</div>
                </div>
                <div class="stat bg-base-200 rounded-lg shadow p-4">
                    <div class="stat-figure text-accent">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                    <div class="stat-title">Upcoming Events</div>
                    <div class="stat-value">{{ getUpcomingEventsCount() }}</div>
                </div>
            </div>

            <!-- Divider -->
            <div class="divider mb-8"></div>

            <!-- Events Section Title -->
            <h2 class="text-2xl font-semibold mb-6">Your Events</h2>

            <!-- Loading Indicator -->
            <div v-if="loading" class="flex justify-center items-center h-64">
                <LoadingIndicator :show-label="true" label="Loading events..." size="lg" />
            </div>

            <!-- No Events View -->
            <div v-else-if="props.events.length === 0" class="text-center py-12">
                <div class="mb-8">
                    <svg
                        class="mx-auto h-32 w-32 text-primary"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                        />
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold mb-4 text-base-content">No Events Yet</h2>
                <p class="text-base-content opacity-75">
                    Start your journey by creating your first event using the Create Event button in
                    the header.
                </p>
            </div>

            <!-- List of Events -->
            <div v-else class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <EventComponent v-for="event in props.events" :key="event.id" :event="event" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/components/layouts/AuthenticatedLayout.vue'
import EventComponent from '@/components/ui/EventComponent.vue'
import LoadingIndicator from '@/components/common/LoadingIndicator.vue'

export default {
    name: 'DashboardPage',
    components: {
        AuthenticatedLayout,
        EventComponent,
        LoadingIndicator
    },
    props: {
        events: {
            type: Array,
            required: true
        }
    },
    setup(props) {
        const page = usePage()
        const user = computed(() => page.props.auth.user)
        const loading = ref(false)

        const getTotalAttendees = () => {
            return props.events.reduce((total, event) => total + (event.attendees_count || 0), 0)
        }

        const getUpcomingEventsCount = () => {
            const now = new Date()
            return props.events.filter((event) => new Date(event.start_date) > now).length
        }

        return {
            user,
            loading,
            props,
            getTotalAttendees,
            getUpcomingEventsCount
        }
    }
}
</script>
