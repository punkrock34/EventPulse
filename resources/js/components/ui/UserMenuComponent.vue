<template>
    <div class="relative">
        <button class="flex items-center space-x-2 focus:outline-none" @click="toggleMenu">
            <img
                :src="avatarUrl"
                alt="User avatar"
                class="w-10 h-10 rounded-full border-2 border-primary"
            />
            <span class="hidden md:inline font-medium">{{ user.name }}</span>
            <i class="fas fa-chevron-down text-xs"></i>
        </button>
        <transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div
                v-if="isOpen"
                class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-base-100 ring-1 ring-black ring-opacity-5"
            >
                <a href="#" class="block px-4 py-2 text-sm hover:bg-base-200" @click="logout"
                    >Logout</a
                >
            </div>
        </transition>
    </div>
</template>

<script>
import { ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

export default {
    name: 'UserMenuComponent',
    setup() {
        const page = usePage()
        const user = computed(() => page.props.auth.user)
        const avatarUrl = computed(
            () =>
                user.value.avatar ||
                `https://www.gravatar.com/avatar/${user.value.email}?d=identicon`
        )
        const isOpen = ref(false)

        const toggleMenu = () => {
            isOpen.value = !isOpen.value
        }

        const logout = () => {
            router.post(route('logout.store'))
            isOpen.value = false
        }

        return { user, avatarUrl, isOpen, toggleMenu, logout }
    }
}
</script>
