<template>
    <header class="bg-base-200 shadow-md">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <a href="/" class="text-2xl font-bold text-primary">EventPulse</a>
                <nav class="flex items-center space-x-4">
                    <button class="btn btn-secondary btn-sm" @click="exploreEvents">
                        <i class="fas fa-compass mr-2"></i> Explore Events
                    </button>
                    <button class="btn btn-primary btn-sm" @click="addEvent">
                        <i class="fas fa-plus mr-2"></i> Create Event
                    </button>
                    <ThemeToggle />
                    <UserMenuComponent />
                </nav>
            </div>
        </div>
    </header>
</template>

<script>
import { router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import ThemeToggle from './ThemeToggleComponent.vue'
import UserMenuComponent from './UserMenuComponent.vue'

export default {
    name: 'AuthenticatedHeader',
    components: { ThemeToggle, UserMenuComponent },
    setup() {
        const addEvent = () => router.visit(route('create-event.index'))
        const exploreEvents = () => router.visit(route('events.index'))

        return { addEvent, exploreEvents }
    }
}
</script>
