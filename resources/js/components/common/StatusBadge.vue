<template>
    <span :class="['badge', badgeClass]">{{ statusLabel }}</span>
</template>

<script>
import { computed } from 'vue'
import { EventStatus } from '@/enums/eventStatus'

export default {
    name: 'StatusBadge',
    props: {
        status: {
            type: String,
            required: true,
            validator: (value) => Object.values(EventStatus).includes(value)
        }
    },
    setup(props) {
        const statusLabel = computed(() => {
            switch (props.status) {
                case EventStatus.UPCOMING:
                    return 'Upcoming'
                case EventStatus.ONGOING:
                    return 'Ongoing'
                case EventStatus.COMPLETED:
                    return 'Completed'
                case EventStatus.CANCELED:
                    return 'Canceled'
                case EventStatus.POSTPONED:
                    return 'Postponed'
                default:
                    return 'Unknown'
            }
        })

        const badgeClass = computed(() => {
            switch (props.status) {
                case EventStatus.UPCOMING:
                    return 'badge-warning'
                case EventStatus.ONGOING:
                    return 'badge-info'
                case EventStatus.COMPLETED:
                    return 'badge-success'
                case EventStatus.CANCELED:
                    return 'badge-error'
                case EventStatus.POSTPONED:
                    return 'badge-ghost'
                default:
                    return 'badge-neutral'
            }
        })

        return {
            statusLabel,
            badgeClass
        }
    }
}
</script>
