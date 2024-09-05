<template>
    <div class="card bg-base-100 shadow-md hover:shadow-lg transition-shadow duration-300">
        <div class="card-body p-4">
            <div class="flex justify-between items-start mb-2">
                <h2 class="card-title text-lg font-semibold">
                    {{ event.title }}
                </h2>
                <StatusBadge :status="event.status" />
            </div>

            <p class="text-sm mb-3 line-clamp-2">{{ event.description }}</p>

            <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                <div class="flex items-center">
                    <i class="fas fa-calendar-alt mr-2 text-primary"></i>
                    <span>{{ formattedStartDate }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-hourglass-end mr-2 text-secondary"></i>
                    <span>{{ formattedEndDate }}</span>
                </div>
            </div>

            <div class="card-actions justify-end">
                <LinkButton
                    :href="route('event.index', { id: event.id })"
                    label="View Details"
                    icon="fas fa-arrow-right"
                    class="btn btn-primary btn-sm"
                />
            </div>
        </div>
    </div>
</template>

<script>
import { computed } from 'vue'
import { route } from 'ziggy-js'
import LinkButton from '@/components/buttons/LinkButton.vue'
import StatusBadge from '@/components/common/StatusBadge.vue'
import { EventStatus } from '@/enums/eventStatus'

export default {
    name: 'EventComponent',
    components: {
        LinkButton,
        StatusBadge
    },
    props: {
        event: {
            type: Object,
            required: true
        }
    },
    setup(props) {
        const formattedStartDate = computed(() => formatDate(props.event.start_date))
        const formattedEndDate = computed(() => formatDate(props.event.end_date))

        function formatDate(date) {
            if (!date) return 'Not set'
            const options = { year: 'numeric', month: 'short', day: 'numeric' }
            return new Date(date).toLocaleDateString(undefined, options)
        }

        return {
            formattedStartDate,
            formattedEndDate,
            route,
            EventStatus
        }
    }
}
</script>
