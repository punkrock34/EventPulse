<template>
    <a
        :href="href"
        :class="[
            'btn btn-outline w-full text-md font-medium rounded-lg flex items-center justify-center',
            'shadow-md hover:shadow-lg',
            'transition duration-150 ease-in-out',
            'bg-base-100 border-base-300 text-primary',
            'hover:bg-base-200 focus:ring-4 focus:ring-base-200'
        ]"
        :target="target"
    >
        <span>{{ label }}</span>
    </a>
</template>

<script>
export default {
    name: 'LinkButton',
    props: {
        href: { type: String, required: true },
        label: { type: String, required: true },
        target: { type: String, default: '_self' }
    }
}
</script>
