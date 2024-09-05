<template>
    <div class="relative mt-2">
        <div class="h-3 w-full bg-base-200 rounded-full overflow-hidden">
            <div
                :class="passwordStrengthClass"
                :style="{ width: passwordStrength + '%' }"
                class="h-full transition-all duration-300"
            ></div>
        </div>
        <p
            v-if="passwordStrengthMessage"
            :class="passwordStrengthMessageClass"
            class="text-sm mt-2 font-medium text-left"
        >
            {{ passwordStrengthMessage }}
        </p>
    </div>
</template>

<script>
import { ref, watch } from 'vue'

export default {
    name: 'PasswordStrengthIndicator',
    props: {
        password: {
            type: String,
            required: true
        }
    },
    setup(props) {
        const passwordStrength = ref(0)
        const passwordStrengthClass = ref('bg-error')
        const passwordStrengthMessage = ref('Weak')
        const passwordStrengthMessageClass = ref('text-error')

        const updateStrength = () => {
            let strength = 0
            const password = props.password

            if (password.length >= 8) strength += 1
            if (password.length >= 12) strength += 1
            if (/[A-Z]/.test(password)) strength += 1
            if (/[a-z]/.test(password)) strength += 1
            if (/[0-9]/.test(password)) strength += 1
            if (/[^a-zA-Z0-9]/.test(password)) strength += 1

            passwordStrength.value = (strength / 6) * 100

            if (strength <= 2) {
                passwordStrengthClass.value = 'bg-error'
                passwordStrengthMessage.value = 'Weak'
                passwordStrengthMessageClass.value = 'text-error'
            } else if (strength <= 4) {
                passwordStrengthClass.value = 'bg-warning'
                passwordStrengthMessage.value = 'Moderate'
                passwordStrengthMessageClass.value = 'text-warning'
            } else {
                passwordStrengthClass.value = 'bg-success'
                passwordStrengthMessage.value = 'Strong'
                passwordStrengthMessageClass.value = 'text-success'
            }
        }

        watch(() => props.password, updateStrength, { immediate: true })

        return {
            passwordStrength,
            passwordStrengthClass,
            passwordStrengthMessage,
            passwordStrengthMessageClass
        }
    }
}
</script>
