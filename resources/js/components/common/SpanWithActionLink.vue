<template>
    <transition name="fade-slide" @enter="enter" @leave="leave">
        <span v-show="actionLink" class="mt-1 mb-1 block">
            <p :class="textClass">
                {{ preActionText }}
            </p>
            <a href="#" class="text-accent hover:underline" @click.prevent="actionLink">
                {{ actionText }}
            </a>
            <p v-if="postActionText" :class="textClass">
                {{ postActionText }}
            </p>
        </span>
    </transition>
</template>

<script>
export default {
    name: 'SpanWithActionLink',
    props: {
        preActionText: { type: String, default: 'Please verify your email address.' },
        actionText: { type: String, default: 'Resend verification email' },
        postActionText: { type: String, default: '' },
        actionLink: { type: Function, default: null },
        isSuccess: { type: Boolean, default: false }
    },
    computed: {
        textClass() {
            return this.isSuccess ? 'text-success text-md' : 'text-error text-md'
        }
    },
    methods: {
        enter(el) {
            el.style.height = 'auto'
            const height = el.offsetHeight
            el.style.height = '0px'
            el.offsetHeight
            el.style.transition = 'height 0.5s ease'
            el.style.height = height + 'px'
        },
        leave(el) {
            el.style.height = el.offsetHeight + 'px'
            el.offsetHeight
            el.style.transition = 'height 0.5s ease'
            el.style.height = '0px'
        }
    }
}
</script>
