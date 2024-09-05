<template>
    <div :class="{ 'border-error': error }" class="form-control mb-6">
        <label :for="id" class="label">
            <span class="label-text text-md font-medium text-base-content">
                {{ label }}
                <span v-if="required" class="text-error">*</span>
            </span>
        </label>
        <input
            :id="id"
            :value="modelValue"
            :type="type"
            :placeholder="placeholder"
            class="input input-bordered w-full"
            @input="$emit('update:modelValue', $event.target.value)"
        />
        <SpanError :error="error" class="mb-1" />
    </div>
</template>

<script>
import SpanError from '@/components/common/SpanError.vue'

export default {
    name: 'TextInput',
    components: {
        SpanError
    },
    props: {
        id: { type: String, required: true },
        modelValue: { type: String, required: true },
        label: { type: String, required: true },
        type: { type: String, default: 'text' },
        placeholder: { type: String, default: '' },
        error: { type: String, default: '' },
        required: { type: Boolean, default: false }
    },
    emits: ['update:modelValue']
}
</script>
