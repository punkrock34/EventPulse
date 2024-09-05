<template>
    <header class="bg-base-100 p-4 text-primary theme-transition">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/">
                <h2 class="text-2xl font-bold">EventPulse</h2>
            </a>
            <ThemeToggle />
        </div>
    </header>
</template>

<script>
import ThemeToggle from './ThemeToggleComponent.vue'

export default {
    name: 'HeaderComponent',
    components: {
        ThemeToggle
    }
}
</script>
