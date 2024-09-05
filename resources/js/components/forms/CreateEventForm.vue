<template>
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title text-2xl mb-6">Create New Event</h2>

            <form class="space-y-6" @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <TextInput
                        id="title"
                        v-model="form.title"
                        label="Event Title"
                        placeholder="Enter event title"
                        :error="form.errors.title"
                        required
                    />

                    <SelectInput
                        id="status"
                        v-model="form.status"
                        label="Status"
                        :options="statusOptions"
                        :error="form.errors.status"
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <DateInput
                        id="start_date"
                        v-model="form.start_date"
                        label="Start Date"
                        :min-date="currentDate"
                        :error="form.errors.start_date"
                        required
                    />

                    <DateInput
                        id="end_date"
                        v-model="form.end_date"
                        label="End Date"
                        :min-date="form.start_date || currentDate"
                        :error="form.errors.end_date"
                        required
                    />
                </div>

                <TextArea
                    id="description"
                    v-model="form.description"
                    label="Description"
                    placeholder="Describe your event"
                    :error="form.errors.description"
                />

                <div class="flex justify-end pt-4">
                    <ButtonWithIcon
                        type="submit"
                        :loading="form.processing"
                        :disabled="form.processing"
                        icon="fas fa-plus"
                        label="Create Event"
                        custom-class="btn btn-primary"
                    />
                </div>
            </form>

            <SpanSuccess :success="form.success" />
            <SpanError :error="form.errors.error" />
        </div>
    </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3'
import { computed } from 'vue'
import { route } from 'ziggy-js'
import TextInput from '@/components/inputs/TextInput.vue'
import TextArea from '@/components/inputs/TextArea.vue'
import SelectInput from '@/components/inputs/SelectInput.vue'
import DateInput from '@/components/inputs/DateInput.vue'
import ButtonWithIcon from '@/components/buttons/ButtonWithIcon.vue'
import SpanError from '@/components/common/SpanError.vue'
import SpanSuccess from '@/components/common/SpanSuccess.vue'
import { EventStatus } from '@/enums/eventStatus'

export default {
    name: 'CreateEventForm',
    components: {
        TextInput,
        TextArea,
        SelectInput,
        DateInput,
        ButtonWithIcon,
        SpanError,
        SpanSuccess
    },
    setup() {
        const form = useForm({
            title: '',
            description: '',
            status: EventStatus.UPCOMING,
            start_date: '',
            end_date: ''
        })

        const currentDate = new Date().toISOString().split('T')[0]

        const statusOptions = computed(() =>
            Object.values(EventStatus).map((status) => ({
                value: status,
                label: formatStatusLabel(status)
            }))
        )

        function submit() {
            form.post(route('create-event.store'), {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    form.reset()
                    form.success = 'Event created successfully!'
                },
                onError: (errors) => {
                    form.errors.error = errors.error
                }
            })
        }

        return {
            form,
            submit,
            currentDate,
            statusOptions
        }
    }
}

function formatStatusLabel(status) {
    return status.charAt(0) + status.slice(1).toLowerCase().replace('_', ' ')
}
</script>
