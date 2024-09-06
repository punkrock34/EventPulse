<template>
    <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300">
        <div class="card-body">
            <div class="flex justify-between items-start">
                <h3 class="card-title text-lg font-bold">{{ event.title }}</h3>
                <div :class="['badge', { 'badge-primary': isOwner, 'badge-secondary': !isOwner }]">
                    {{ isOwner ? 'Your Event' : 'Joined' }}
                </div>
            </div>

            <p class="text-base-content/70 mt-2">{{ truncateDescription(event.description) }}</p>

            <div class="flex items-center mt-4 text-sm">
                <i class="fas fa-calendar-day mr-2"></i>
                <span>{{ formatDate(event.start_date) }}</span>
            </div>

            <div class="flex items-center mt-2 text-sm">
                <i class="fas fa-map-marker-alt mr-2"></i>
                <span>{{ event.location || 'Location TBA' }}</span>
            </div>

            <div class="flex items-center mt-2 text-sm">
                <i class="fas fa-users mr-2"></i>
                <span>{{ event.attendees_count || 0 }} attendees</span>
            </div>

            <div class="card-actions justify-end mt-4">
                <Link :href="route('event.index', event.id)" class="btn btn-primary btn-sm">
                    {{ isOwner ? 'Manage Event' : 'View Event' }}
                    <i class="fas fa-arrow-right ml-2"></i>
                </Link>
            </div>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

export default {
    name: 'EventCard',
    components: {
        // eslint-disable-next-line vue/no-reserved-component-names
        Link
    },
    props: {
        event: {
            type: Object,
            required: true
        },
        isOwner: {
            type: Boolean,
            default: false
        }
    },
    setup() {
        return {
            route
        }
    },
    methods: {
        truncateDescription(description) {
            return description?.length > 100 ? description.substring(0, 97) + '...' : description
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            })
        }
    }
}
</script>
