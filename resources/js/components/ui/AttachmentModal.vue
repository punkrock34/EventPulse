<template>
    <TransitionRoot appear :show="visible" as="template">
        <Dialog as="div" @close="close" class="relative z-50">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black bg-opacity-25" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel
                            class="w-full max-w-3xl transform overflow-hidden rounded-2xl bg-base-100 p-6 text-left align-middle shadow-xl transition-all"
                        >
                            <DialogTitle as="h3" class="text-2xl font-bold leading-6 mb-4">
                                {{ isOwner ? 'Manage Attachments' : 'View Attachments' }}
                            </DialogTitle>
                            <button
                                @click="close"
                                class="absolute top-4 right-4 text-neutral-content hover:text-neutral-focus transition-colors"
                            >
                                <svg
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>

                            <div
                                v-if="error"
                                class="mb-4 p-4 bg-error text-error-content rounded-md"
                            >
                                {{ error }}
                            </div>

                            <div v-if="isOwner" class="mb-6">
                                <label
                                    for="file-upload"
                                    class="flex justify-center items-center px-4 py-6 border-2 border-dashed rounded-lg cursor-pointer border-primary hover:border-primary-focus transition-colors"
                                >
                                    <div class="space-y-1 text-center">
                                        <svg
                                            class="mx-auto h-12 w-12 text-primary"
                                            stroke="currentColor"
                                            fill="none"
                                            viewBox="0 0 48 48"
                                            aria-hidden="true"
                                        >
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                        <div class="flex text-sm text-base-content">
                                            <label
                                                for="file-upload"
                                                class="relative cursor-pointer rounded-md font-medium text-primary hover:text-primary-focus"
                                            >
                                                <span>Upload a file</span>
                                                <input
                                                    id="file-upload"
                                                    name="file-upload"
                                                    type="file"
                                                    class="sr-only"
                                                    accept="image/*"
                                                    @change="onFileChange"
                                                />
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-base-content opacity-60">
                                            PNG, JPG, GIF up to 10MB
                                        </p>
                                    </div>
                                </label>
                            </div>

                            <div v-if="isOwner && fileSelected" class="mb-6">
                                <div
                                    class="flex items-center justify-between p-4 bg-base-200 rounded-lg"
                                >
                                    <div class="flex items-center">
                                        <svg
                                            class="h-8 w-8 text-primary"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        <span class="ml-2 text-sm font-medium text-base-content">{{
                                            fileName
                                        }}</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button
                                            type="button"
                                            class="btn btn-primary btn-sm"
                                            @click="uploadAttachment"
                                        >
                                            Upload
                                        </button>
                                        <button
                                            type="button"
                                            class="btn btn-ghost btn-sm"
                                            @click="cancelAttachment"
                                        >
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div v-if="images.length">
                                    <h4 class="text-lg font-semibold text-base-content mb-4">
                                        Uploaded Images
                                    </h4>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                        <div
                                            v-for="image in images"
                                            :key="image.id"
                                            class="relative group"
                                        >
                                            <img
                                                :src="`/storage/${image.path}`"
                                                :alt="image.name"
                                                class="h-32 w-full object-cover rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105"
                                            />
                                            <div
                                                class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center space-x-2 rounded-lg"
                                            >
                                                <button
                                                    type="button"
                                                    class="text-white hover:text-primary transition-colors p-2 rounded-full bg-black bg-opacity-50 hover:bg-opacity-75"
                                                    title="View image"
                                                    @click.stop="
                                                        openImagePreview(`/storage/${image.path}`)
                                                    "
                                                >
                                                    <svg
                                                        class="h-6 w-6"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                        />
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                        />
                                                    </svg>
                                                </button>
                                                <button
                                                    v-if="isOwner"
                                                    type="button"
                                                    class="text-white hover:text-error transition-colors p-2 rounded-full bg-black bg-opacity-50 hover:bg-opacity-75"
                                                    title="Delete image"
                                                    @click.stop="removeAttachment(image)"
                                                >
                                                    <svg
                                                        class="h-6 w-6"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                        />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="text-center text-base-content opacity-60 py-8">
                                    No images uploaded yet.
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <ImagePreview v-if="imagePreviewUrl" :url="imagePreviewUrl" @close="closeImagePreview" />
</template>

<script>
import { ref, watch, computed } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { route } from 'ziggy-js'
import axios from 'axios'
import ImagePreview from './ImagePreview.vue'

export default {
    name: 'AttachmentModal',
    components: {
        // eslint-disable-next-line vue/no-reserved-component-names
        Dialog,
        DialogPanel,
        DialogTitle,
        TransitionChild,
        TransitionRoot,
        ImagePreview
    },
    props: {
        visible: { type: Boolean, required: true },
        attachments: { type: Array, required: true },
        eventId: { type: Number, required: true },
        isOwner: { type: Boolean, required: true }
    },
    emits: ['close', 'update:attachments'],
    setup(props, { emit }) {
        const file = ref(null)
        const fileName = ref('')
        const fileSelected = ref(false)
        const error = ref('')
        const imagePreviewUrl = ref(null)
        const localAttachments = ref([...props.attachments])

        watch(
            () => props.attachments,
            (newAttachments) => {
                localAttachments.value = [...newAttachments]
            }
        )

        const images = computed(() =>
            localAttachments.value.filter((attachment) =>
                /\.(jpg|jpeg|png|gif|bmp|webp)$/i.test(attachment.name)
            )
        )

        const onFileChange = (event) => {
            file.value = event.target.files[0]
            if (file.value) {
                fileName.value = file.value.name
                fileSelected.value = true
            }
        }

        const cancelAttachment = () => {
            fileSelected.value = false
            fileName.value = ''
            file.value = null
            const fileInput = document.getElementById('file-upload')
            if (fileInput) fileInput.value = null
            error.value = ''
        }

        const uploadAttachment = async () => {
            if (!file.value) return

            const formData = new FormData()
            formData.append('attachment', file.value)
            formData.append('event_id', props.eventId)

            try {
                const response = await axios.post(route('attachments.store'), formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                })

                if (response.data && response.data.attachment) {
                    localAttachments.value = [...localAttachments.value, response.data.attachment]
                    emit('update:attachments', localAttachments.value)
                    cancelAttachment()
                } else {
                    error.value = 'Unexpected response format'
                }
            } catch (err) {
                error.value = 'Error uploading attachment'
            }
        }

        const removeAttachment = async (attachment) => {
            try {
                await axios.delete(route('attachments.destroy', { attachment: attachment.id }))
                localAttachments.value = localAttachments.value.filter(
                    (att) => att.id !== attachment.id
                )
                emit('update:attachments', localAttachments.value)
            } catch (err) {
                error.value = 'Error removing attachment'
                console.error('Error removing attachment:', err)
            }
        }

        const openImagePreview = (url) => {
            emit('close')
            imagePreviewUrl.value = url
        }

        const closeImagePreview = () => {
            imagePreviewUrl.value = null
        }

        const close = () => {
            emit('close')
        }

        return {
            file,
            fileName,
            fileSelected,
            localAttachments,
            onFileChange,
            cancelAttachment,
            uploadAttachment,
            removeAttachment,
            openImagePreview,
            closeImagePreview,
            images,
            error,
            imagePreviewUrl,
            close
        }
    }
}
</script>
