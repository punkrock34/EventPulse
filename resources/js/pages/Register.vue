<template>
    <DefaultLayout>
        <SnackbarNotification ref="snackbarRef" />
        <div class="flex flex-grow items-center justify-center w-full">
            <div
                class="w-full max-w-xl p-6 sm:p-8 bg-base-100 rounded-lg shadow-md theme-transition"
            >
                <h1 class="text-2xl font-bold text-base-content mb-6 text-center">
                    Sign up for an account
                </h1>
                <RegisterForm />
                <div
                    class="my-4 flex items-center before:mt-0.5 before:flex-1 before:border-t before:border-neutral after:mt-0.5 after:flex-1 after:border-t after:border-neutral"
                >
                    <p class="mx-4 mb-0 text-center font-semibold text-base-content">Or</p>
                </div>
                <ButtonWithIcon
                    label="Sign up with Google"
                    icon="https://www.svgrepo.com/show/355037/google.svg"
                    alt="Google logo"
                    type="button"
                    :custom-class="'btn-primary text-white'"
                    @click="signInWithGoogle"
                />
                <div class="mt-4 text-md font-medium text-base-content text-center">
                    Already have an account?
                    <a :href="route('login.index')" class="text-accent hover:underline"> Log in </a>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

<script>
import { ref, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import DefaultLayout from '@/components/layouts/DefaultLayout.vue'
import RegisterForm from '@/components/forms/RegisterForm.vue'
import ButtonWithIcon from '@/components/buttons/ButtonWithIcon.vue'
import { auth, provider, signInWithPopup } from '@/firebase'
import axios from 'axios'
import SnackbarNotification from '@/components/ui/SnackbarNotification.vue'

export default {
    name: 'RegisterPage',
    components: {
        DefaultLayout,
        RegisterForm,
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
