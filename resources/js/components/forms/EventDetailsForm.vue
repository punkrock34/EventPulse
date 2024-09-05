<template>
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div
                class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4"
            >
                <h2 class="card-title text-2xl">Event Details</h2>
                <div class="badge badge-lg" :class="statusBadgeClass">{{ statusLabel }}</div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-base-200 p-4 rounded-box mb-6">
                <div class="flex flex-col">
                    <span class="text-xs opacity-70">Created</span>
                    <span class="font-semibold">{{ formattedCreatedAt }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs opacity-70">Starts</span>
                    <span class="font-semibold">{{ formattedStartDate }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs opacity-70">Ends</span>
                    <span class="font-semibold">{{ formattedEndDate }}</span>
                </div>
            </div>

            <form class="space-y-6" @submit.prevent="handleSubmit">
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
                        :min-date="minDate"
                        :error="form.errors.start_date"
                    />

                    <DateInput
                        id="end_date"
                        v-model="form.end_date"
                        label="End Date"
                        :min-date="form.start_date"
                        :error="form.errors.end_date"
                    />
                </div>

                <TextArea
                    id="description"
                    v-model="form.description"
                    label="Description"
                    placeholder="Describe your event"
                    :error="form.errors.description"
                />

                <TextArea
                    id="notes"
                    v-model="form.notes"
                    label="Notes"
                    placeholder="Any additional notes"
                    rows="4"
                    :error="form.errors.notes"
                />

                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                    <ButtonWithIcon
                        class="btn btn-outline btn-primary w-full sm:w-auto"
                        icon="fas fa-paperclip"
                        label="Manage Attachments"
                        @click="toggleModal"
                    />

                    <ButtonWithIcon
                        type="submit"
                        :loading="form.processing"
                        :disabled="form.processing"
                        icon="fas fa-save"
                        label="Save Changes"
                        custom-class="btn btn-primary w-full sm:w-auto"
                    />
                </div>
            </form>

            <SpanSuccess :success="form.success" />
            <SpanError :error="form.errors.error" />

            <AttachmentModal
                :visible="showModal"
                :attachments="form.attachments"
                :event-id="form.id"
                @close="toggleModal"
            />
        </div>
    </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import { route } from 'ziggy-js'
import TextInput from '@/components/inputs/TextInput.vue'
import TextArea from '@/components/inputs/TextArea.vue'
import SelectInput from '@/components/inputs/SelectInput.vue'
import DateInput from '@/components/inputs/DateInput.vue'
import ButtonWithIcon from '@/components/buttons/ButtonWithIcon.vue'
import SpanError from '@/components/common/SpanError.vue'
import SpanSuccess from '@/components/common/SpanSuccess.vue'
import AttachmentModal from '@/components/ui/AttachmentModal.vue'
import { EventStatus } from '@/enums/eventStatus'

export default {
    name: 'EventDetailsForm',
    components: {
        TextInput,
        TextArea,
        SelectInput,
        DateInput,
        ButtonWithIcon,
        SpanError,
        SpanSuccess,
        AttachmentModal
    },
    props: {
        event: {
            type: Object,
            required: true
        }
    },
    emits: ['submit'],
    setup(props) {
        const form = useForm({
            id: props.event.id,
            title: props.event.title,
            description: props.event.description || '',
            status: props.event.status,
            start_date: props.event.start_date
                ? new Date(props.event.start_date).toISOString().split('T')[0]
                : '',
            end_date: props.event.end_date
                ? new Date(props.event.end_date).toISOString().split('T')[0]
                : '',
            notes: props.event.notes || '',
            attachments: props.event.attachments || []
        })

        const showModal = ref(false)
        const minDate = ref(getMinDate())

        const formattedCreatedAt = computed(() => formatDate(props.event.created_at))
        const formattedStartDate = computed(() => formatDate(props.event.start_date))
        const formattedEndDate = computed(() => formatDate(props.event.end_date))

        const statusOptions = computed(() =>
            Object.values(EventStatus).map((status) => ({
                value: status,
                label: formatStatusLabel(status)
            }))
        )

        const statusBadgeClass = computed(() => {
            switch (form.status) {
                case EventStatus.UPCOMING:
                    return 'badge-info'
                case EventStatus.ONGOING:
                    return 'badge-warning'
                case EventStatus.COMPLETED:
                    return 'badge-success'
                case EventStatus.CANCELED:
                    return 'badge-error'
                case EventStatus.POSTPONED:
                    return 'badge-secondary'
                default:
                    return 'badge-ghost'
            }
        })

        const statusLabel = computed(() => formatStatusLabel(form.status))

        watch(
            () => props.event,
            (newEvent) => {
                Object.keys(form).forEach((key) => {
                    if (key in newEvent) {
                        form[key] = newEvent[key]
                    }
                })
                if (newEvent.end_date) {
                    form.end_date = new Date(newEvent.end_date).toISOString().split('T')[0]
                }
                if (newEvent.start_date) {
                    form.start_date = new Date(newEvent.start_date).toISOString().split('T')[0]
                }
            },
            { deep: true }
        )

        function handleSubmit() {
            form.put(route('event.update', form.id), {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (page) => {
                    const updatedEvent = page.props.event
                    Object.keys(form).forEach((key) => {
                        if (key in updatedEvent) {
                            form[key] = updatedEvent[key]
                        }
                    })
                    if (updatedEvent.end_date) {
                        form.end_date = new Date(updatedEvent.end_date).toISOString().split('T')[0]
                    }
                    if (updatedEvent.start_date) {
                        form.start_date = new Date(updatedEvent.start_date)
                            .toISOString()
                            .split('T')[0]
                    }
                    form.success = page.props.success
                },
                onError: (errors) => {
                    form.errors.error = errors.error
                }
            })
        }

        function toggleModal() {
            showModal.value = !showModal.value
        }

        return {
            form,
            minDate,
            formattedCreatedAt,
            formattedStartDate,
            formattedEndDate,
            statusOptions,
            showModal,
            handleSubmit,
            toggleModal,
            statusBadgeClass,
            statusLabel
        }
    }
}

function getMinDate() {
    return new Date(Date.now() + 86400000).toISOString().split('T')[0]
}

function formatDate(date) {
    if (!date) return 'Not set'
    const options = { year: 'numeric', month: 'long', day: 'numeric' }
    return new Date(date).toLocaleDateString(undefined, options)
}

function formatStatusLabel(status) {
    return status.charAt(0) + status.slice(1).toLowerCase().replace('_', ' ')
}
</script>
