<template>
    <button :type="type" :disabled="loading" :class="buttonClasses" @click="$emit('click')">
        <template v-if="!loading">
            <i v-if="isFontAwesomeIcon" :class="[icon, 'h-5 w-5', label ? 'mr-2' : '']"></i>
            <img v-else :class="['h-5 w-5', label ? 'mr-2' : '']" :src="icon" :alt="alt" />
        </template>
        <LoadingIndicator v-if="loading" size="md" label="Loading..." />
        <span v-if="label">{{ label }}</span>
    </button>
</template>

<script>
import LoadingIndicator from '@/components/common/LoadingIndicator.vue'
import { twMerge } from 'tailwind-merge'

export default {
    name: 'ButtonWithIcon',
    components: {
        LoadingIndicator
    },
    props: {
        type: { type: String, default: 'button' },
        label: { type: String, default: '' },
        alt: { type: String, default: '' },
        icon: { type: String, required: true },
        loading: { type: Boolean, default: false },
        customClass: { type: String, default: '' }
    },
    emits: ['click'],
    computed: {
        isFontAwesomeIcon() {
            return this.icon.includes('fa-') || !this.icon.match(/\.(jpeg|jpg|gif|png|svg)$/i)
        },

        buttonClasses() {
            return twMerge(
                'btn btn-outline w-full text-md font-medium rounded-lg flex items-center justify-center',
                this.loading ? 'btn-disabled' : 'shadow-md hover:shadow-lg',
                'transition duration-150 ease-in-out',
                'bg-base-100 border-base-300 text-primary',
                'hover:bg-base-200 focus:ring-4 focus:ring-base-200',
                !this.label ? 'justify-center' : '',
                this.customClass
            )
        }
    }
}
</script>
