<script setup>
import { computed, useSlots } from 'vue';

const slots = useSlots();

defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});

const hasIcon = computed(() => {
    return !! slots.icon;
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150">
        <div class="w-full flex items-center gap-2">
            <div v-if="hasIcon"><slot name="icon"></slot></div>
            <slot />
        </div>
    </button>
</template>
