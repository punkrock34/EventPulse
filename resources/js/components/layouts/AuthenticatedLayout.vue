<template>
    <div class="flex flex-col min-h-screen bg-base-100 text-base-content">
        <AuthenticatedHeader />
        <main class="flex-grow container mx-auto px-4 py-8">
            <slot />
        </main>
        <FooterComponent />
    </div>
</template>

<script>
import AuthenticatedHeader from '@/components/ui/AuthenticatedHeader.vue'
import FooterComponent from '@/components/ui/FooterComponent.vue'

export default {
    name: 'AuthenticatedLayout',
    components: {
        AuthenticatedHeader,
        FooterComponent
    }
}
</script>
