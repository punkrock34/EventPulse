<template>
    <div :class="{ 'border-error': error }" class="form-control mb-6">
        <label :for="id" class="label">
            <span class="label-text text-md font-medium text-base-content">
                {{ label }}
                <span v-if="required" class="text-error">*</span>
            </span>
        </label>
        <textarea
            :id="id"
            v-model="internalValue"
            :placeholder="placeholder"
            class="textarea textarea-bordered w-full"
            rows="4"
            @input="updateValue"
        ></textarea>
        <SpanError :error="error" class="mt-1" />
    </div>
</template>

<script>
import SpanError from '@/components/common/SpanError.vue'

export default {
    name: 'TextArea',
    components: {
        SpanError
    },
    props: {
        id: { type: String, required: true },
        modelValue: { type: String, required: true },
        label: { type: String, required: true },
        placeholder: { type: String, default: '' },
        error: { type: String, default: '' },
        required: { type: Boolean, default: false }
    },
    emits: ['update:modelValue'],
    data() {
        return {
            internalValue: this.modelValue
        }
    },
    watch: {
        modelValue(newValue) {
            this.internalValue = newValue
        }
    },
    methods: {
        updateValue() {
            this.$emit('update:modelValue', this.internalValue)
        }
    }
}
</script>
