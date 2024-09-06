<template>
    <AuthenticatedLayout>
        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">Event Management</h1>
                <a :href="route('dashboard.index')" class="btn btn-ghost btn-sm normal-case">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 mr-2"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        />
                    </svg>
                    Back to Dashboard
                </a>
            </div>

            <div class="bg-base-200 p-6 rounded-box">
                <!-- Pass the isOwner prop to EventDetailsForm -->
                <EventDetailsForm
                    :event="localEvent"
                    :is-owner="isOwner"
                    @update="handleEventUpdate"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { reactive } from 'vue'
import { route } from 'ziggy-js'
import AuthenticatedLayout from '@/components/layouts/AuthenticatedLayout.vue'
import EventDetailsForm from '@/components/forms/EventDetailsForm.vue'

export default {
    name: 'EventDetails',
    components: {
        AuthenticatedLayout,
        EventDetailsForm
    },
    props: {
        event: {
            type: Object,
            required: true
        },
        isOwner: {
            type: Boolean,
            required: true
        }
    },
    setup(props) {
        const localEvent = reactive({ ...props.event })

        return {
            localEvent,
            route,
            // eslint-disable-next-line vue/no-dupe-keys
            isOwner: props.isOwner // Make sure to expose this prop in the setup function
        }
    }
}
</script>
