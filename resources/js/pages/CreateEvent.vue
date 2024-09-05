<template>
    <AuthenticatedLayout>
        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">Create New Event</h1>
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
                <CreateEventForm />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { route } from 'ziggy-js'
import AuthenticatedLayout from '@/components/layouts/AuthenticatedLayout.vue'
import CreateEventForm from '@/components/forms/CreateEventForm.vue'

export default {
    name: 'CreateEvent',
    components: {
        AuthenticatedLayout,
        CreateEventForm
    },
    setup() {
        return {
            route
        }
    }
}
</script>
