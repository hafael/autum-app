<script setup>
import { computed, useSlots } from 'vue';
import { Link } from '@inertiajs/vue3';

const slots = useSlots();

defineProps({
    href: String,
    as: String,
});

const hasIcon = computed(() => {
    return !! slots.icon;
});
</script>

<template>
    <div>
        <button v-if="as == 'button'" type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
            <div class="w-full flex items-center gap-2">
                <div v-if="hasIcon"><slot name="icon"></slot></div>
                <slot />
            </div>
        </button>

        <a v-else-if="as =='a'" :href="href" class="block px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
            <div class="w-full flex items-center gap-2">
                <div v-if="hasIcon"><slot name="icon"></slot></div>
                <slot />
            </div>
        </a>

        <Link v-else :href="href" class="block px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
            <div class="w-full flex items-center gap-2">
                <div v-if="hasIcon"><slot name="icon"></slot></div>
                <slot />
            </div>
        </Link>
    </div>
</template>
