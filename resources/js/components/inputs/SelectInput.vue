<template>
    <div :class="{ 'border-error': error }" class="form-control mb-6">
        <label :for="id" class="label">
            <span class="label-text text-md font-medium text-base-content">
                {{ label }}
                <span v-if="required" class="text-error">*</span>
            </span>
        </label>
        <select
            :id="id"
            v-model="internalValue"
            class="select select-bordered w-full"
            @change="updateValue"
        >
            <option
                v-for="option in options"
                :key="option.value"
                :value="option.value"
                :selected="option.value === modelValue"
            >
                {{ option.label }}
            </option>
        </select>
        <SpanError :error="error" class="mt-1" />
    </div>
</template>

<script>
import SpanError from '@/components/common/SpanError.vue'

export default {
    name: 'SelectInput',
    components: {
        SpanError
    },
    props: {
        id: { type: String, required: true },
        modelValue: { type: String, required: true },
        label: { type: String, required: true },
        options: { type: Array, required: true },
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
