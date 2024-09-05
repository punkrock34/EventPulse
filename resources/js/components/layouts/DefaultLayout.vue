<template>
    <div class="flex flex-col min-h-screen theme-transition bg-base-200 text-base-content">
        <HeaderComponent />
        <div class="container h-full w-full flex-grow mx-auto px-4 py-6 sm:py-8">
            <slot />
        </div>
        <FooterComponent />
    </div>
</template>

<script>
import HeaderComponent from '@/components/ui/HeaderComponent.vue'
import FooterComponent from '@/components/ui/FooterComponent.vue'

export default {
    name: 'DefaultLayout',
    components: {
        HeaderComponent,
        FooterComponent
    }
}
</script>
