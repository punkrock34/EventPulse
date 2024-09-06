<template>
    <AuthenticatedLayout>
        <div class="p-6 space-y-12">
            <header class="space-y-4">
                <h1 class="text-4xl font-bold">Dashboard</h1>
                <p class="text-xl text-base-content/70">Welcome back, {{ user.name }}!</p>
            </header>

            <!-- Quick Stats -->
            <section class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <StatCard
                    title="Your Events"
                    :value="props.events.length"
                    icon="fas fa-calendar-check"
                    color="primary"
                />
                <StatCard
                    title="Total Attendees"
                    :value="getTotalAttendees()"
                    icon="fas fa-users"
                    color="secondary"
                />
                <StatCard
                    title="Upcoming Events"
                    :value="getUpcomingEventsCount()"
                    icon="fas fa-star"
                    color="accent"
                />
                <StatCard
                    title="Joined Events"
                    :value="props.joinedEvents.length"
                    icon="fas fa-handshake"
                    color="info"
                />
            </section>

            <!-- Your Events Section -->
            <section>
                <h2 class="text-3xl font-semibold mb-6">Your Events</h2>
                <div v-if="loading" class="flex justify-center items-center h-64">
                    <LoadingIndicator :show-label="true" label="Loading events..." size="lg" />
                </div>
                <div
                    v-else-if="props.events.length === 0"
                    class="bg-base-200 rounded-box p-8 text-center"
                >
                    <i class="fas fa-calendar-plus text-6xl text-primary mb-4"></i>
                    <h3 class="text-2xl font-semibold mb-2">No Events Created Yet</h3>
                    <p class="text-base-content/70 mb-4">
                        Start your journey of creating events and sharing them with the world.
                    </p>
                    <button class="btn btn-primary" @click="createEvent">
                        Create Your First Event
                    </button>
                </div>
                <div v-else class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <EventCard
                        v-for="event in props.events"
                        :key="event.id"
                        :event="event"
                        :is-owner="true"
                    />
                </div>
            </section>

            <!-- Joined Events Section -->
            <section>
                <h2 class="text-3xl font-semibold mb-6">Events You've Joined</h2>
                <div v-if="loading" class="flex justify-center items-center h-64">
                    <LoadingIndicator
                        :show-label="true"
                        label="Loading joined events..."
                        size="lg"
                    />
                </div>
                <div
                    v-else-if="props.joinedEvents.length === 0"
                    class="bg-base-200 rounded-box p-8 text-center"
                >
                    <i class="fas fa-search text-6xl text-secondary mb-4"></i>
                    <h3 class="text-2xl font-semibold mb-2">No Joined Events</h3>
                    <p class="text-base-content/70 mb-4">
                        Explore and join events to see them listed here.
                    </p>
                    <button class="btn btn-secondary" @click="exploreEvents">Explore Events</button>
                </div>
                <div v-else class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <EventCard
                        v-for="event in props.joinedEvents"
                        :key="event.id"
                        :event="event"
                        :is-owner="false"
                    />
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import AuthenticatedLayout from '@/components/layouts/AuthenticatedLayout.vue'
import StatCard from '@/components/ui/StatCard.vue'
import EventCard from '@/components/ui/EventCard.vue'
import LoadingIndicator from '@/components/common/LoadingIndicator.vue'
import { EventStatus } from '@/enums/eventStatus'

export default {
    name: 'DashboardPage',
    components: {
        AuthenticatedLayout,
        StatCard,
        EventCard,
        LoadingIndicator
    },
    props: {
        events: {
            type: Array,
            required: true
        },
        joinedEvents: {
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
            return props.events.filter((event) => event.status === EventStatus.UPCOMING).length
        }

        const createEvent = () => router.visit(route('create-event.index'))
        const exploreEvents = () => router.visit(route('events.index'))

        return {
            user,
            loading,
            props,
            getTotalAttendees,
            getUpcomingEventsCount,
            createEvent,
            exploreEvents
        }
    }
}
</script>
