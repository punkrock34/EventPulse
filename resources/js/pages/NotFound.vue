<template>
    <DefaultLayout>
        <div class="flex flex-grow items-center justify-center w-full">
            <div
                class="w-full max-w-md p-6 sm:p-8 bg-base-100 rounded-lg shadow-md theme-transition"
            >
                <h1 class="text-6xl font-bold text-base-content mb-6">
                    404 <span role="img" aria-label="confused face">😕</span>
                </h1>
                <h2 class="text-2xl font-semibold text-base-content mb-4">Oops! You seem lost.</h2>
                <p class="text-base-content mb-6">We can't find the page you're looking for.</p>
                <a href="/" class="inline-block btn btn-primary">
                    <span role="img" aria-label="house with garden">🏡</span> Take me home
                </a>
            </div>
        </div>
    </DefaultLayout>
</template>

<script>
import DefaultLayout from '@/components/layouts/DefaultLayout.vue'

export default {
    name: 'NotFoundPage',
    components: {
        DefaultLayout
    }
}
</script>
