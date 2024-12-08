<template>
    <button @click="toggleNotifications" 
        class="cursor-pointer relative inline-flex items-center justify-center p-2 text-gray-600 focus:text-gray-800 hover:text-gray-800 active:text-gray-800 dark:hover:text-gray-300 dark:active:text-gray-300">
            <BellIcon class="w-6 h-6" />
            <span class="w-3 h-3 top-1 right-0 absolute" v-show="unreadNotifications">
                <span class="w-full h-full rounded-full bg-blue-200 dark:bg-blue-400 animate-ping absolute top-0 left-0"></span>
                <span class="w-3 h-3 rounded-full bg-blue-400 dark:bg-blue-600 absolute top-0 left-0"></span>
            </span>
    </button>
</template>

<script>

    import { BellIcon } from '@heroicons/vue/24/outline'
    
    export default {

        components: {
            BellIcon,
        },

        props: {
            unreadNotifications: {
                type: Boolean,
                default: false
            }
        },

        data() {
            return {
                opened: false,
            }
        },

        methods: {
            toggleNotifications() {
                this.opened = !this.opened;
                this.$emit('toggle', this.opened);
            }
        }
    }
</script>