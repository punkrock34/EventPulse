<template>
    <div
        class="stat bg-base-200 rounded-lg shadow-lg p-6 transition-all duration-300 hover:shadow-xl"
    >
        <div class="stat-figure" :class="`text-${color}`">
            <i :class="[icon, 'text-3xl']"></i>
        </div>
        <div class="stat-title text-base-content/70">{{ title }}</div>
        <div class="stat-value text-4xl font-bold mt-2">{{ value }}</div>
    </div>
</template>

<script>
export default {
    name: 'StatCard',
    props: {
        title: {
            type: String,
            required: true
        },
        value: {
            type: [Number, String],
            required: true
        },
        icon: {
            type: String,
            required: true
        },
        color: {
            type: String,
            default: 'primary',
            validator: (value) => ['primary', 'secondary', 'accent', 'info'].includes(value)
        }
    }
}
</script>
