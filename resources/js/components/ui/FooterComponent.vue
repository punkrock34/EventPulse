<template>
    <footer class="bg-base-200 text-base-content">
        <div class="container mx-auto py-6 px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <h3 class="text-lg font-semibold">EventPulse</h3>
                    <p class="text-sm">&copy; {{ currentYear }} All rights reserved</p>
                </div>
                <nav class="flex space-x-4">
                    <a href="#" class="hover:text-primary transition-colors">About</a>
                    <a href="#" class="hover:text-primary transition-colors">Privacy</a>
                    <a href="#" class="hover:text-primary transition-colors">Terms</a>
                </nav>
            </div>
        </div>
    </footer>
</template>

<script>
import { computed } from 'vue'

export default {
    name: 'FooterComponent',
    setup() {
        const currentYear = computed(() => new Date().getFullYear())
        return { currentYear }
    }
}
</script>
