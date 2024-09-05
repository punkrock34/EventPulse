<template>
    <div :class="{ 'border-error': error }" class="form-control mb-6">
        <label :for="id" class="label">
            <span class="label-text text-md font-medium text-base-content">
                {{ label }}
            </span>
        </label>
        <div class="relative">
            <input
                :id="id"
                :value="modelValue"
                :type="visible ? 'text' : 'password'"
                :placeholder="placeholder"
                autocomplete="new-password"
                class="input input-bordered w-full pr-10"
                @input="$emit('update:modelValue', $event.target.value)"
            />
            <button
                type="button"
                class="absolute inset-y-0 right-0 flex items-center pr-3 text-base-content"
                @click="toggleVisibility"
            >
                <i :class="visible ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
            </button>
        </div>
        <SpanError :error="error" class="mb-1" />
    </div>
</template>

<script>
import SpanError from '@/components/common/SpanError.vue'

export default {
    name: 'PasswordInput',
    components: {
        SpanError
    },
    props: {
        id: { type: String, required: true },
        modelValue: { type: String, required: true },
        label: { type: String, required: true },
        placeholder: { type: String, default: '' },
        error: { type: String, default: '' }
    },
    emits: ['update:modelValue'],
    data() {
        return {
            visible: false
        }
    },
    methods: {
        toggleVisibility() {
            this.visible = !this.visible
        }
    }
}
</script>
