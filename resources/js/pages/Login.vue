<template>
    <DefaultLayout>
        <SnackbarNotification ref="snackbarRef" />
        <div class="flex flex-grow items-center justify-center w-full">
            <div
                class="w-full max-w-xl p-6 sm:p-8 bg-base-100 dark:bg-base-300 rounded-lg shadow-md theme-transition"
            >
                <h1 class="text-3xl font-bold text-primary mb-6 text-center">Welcome Back!</h1>
                <LoginForm :forgot-password-route="route('forgot-password.index')" />
                <div
                    class="my-4 flex items-center before:mt-0.5 before:flex-1 before:border-t before:border-neutral after:mt-0.5 after:flex-1 after:border-t after:border-neutral"
                >
                    <p class="mx-4 mb-0 text-center font-semibold text-primary text-xl">Or</p>
                </div>
                <ButtonWithIcon
                    label="Sign in with Google"
                    icon="https://www.svgrepo.com/show/355037/google.svg"
                    alt="Google logo"
                    type="button"
                    :custom-class="'btn-primary text-white'"
                    @click="signInWithGoogle"
                />
                <div class="mt-4 text-md font-medium text-secondary text-center">
                    Not registered?
                    <a :href="route('register.index')" class="text-accent hover:underline">
                        Create account
                    </a>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

<script>
import { ref, onMounted } from 'vue'
import { route } from 'ziggy-js'
import { usePage } from '@inertiajs/vue3'
import DefaultLayout from '@/components/layouts/DefaultLayout.vue'
import LoginForm from '@/components/forms/LoginForm.vue'
import ButtonWithIcon from '@/components/buttons/ButtonWithIcon.vue'
import { auth, provider, signInWithPopup } from '@/firebase'
import axios from 'axios'
import SnackbarNotification from '@/components/ui/SnackbarNotification.vue'

export default {
    name: 'LoginPage',
    components: {
        DefaultLayout,
        LoginForm,
        ButtonWithIcon,
        SnackbarNotification
    },
    setup() {
        const { props } = usePage()
        const snackbarRef = ref(null)

        onMounted(() => {
            if (props.success) {
                snackbarRef.value.show(props.success)
            }
        })

        const signInWithGoogle = async () => {
            try {
                const result = await signInWithPopup(auth, provider)
                const idToken = await result.user.getIdToken()
                await axios.post(route('login.google.store'), { idToken: idToken })
                window.location.href = route('dashboard.index')
            } catch (error) {
                snackbarRef.value.show('Failed to sign in with Google. Please try again.')
            }
        }

        return {
            route,
            signInWithGoogle,
            snackbarRef
        }
    }
}
</script>
