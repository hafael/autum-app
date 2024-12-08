<script setup>
import { computed, useSlots } from 'vue';

const slots = useSlots();

defineProps({
    type: {
        type: String,
        default: 'button',
    },
});

const hasIcon = computed(() => {
    return !! slots.icon;
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
        <div class="w-full flex items-center gap-2">
            <div v-if="hasIcon"><slot name="icon"></slot></div>
            <slot />
        </div>
    </button>
</template>
