<template>
    <button class="cursor-pointer rounded-md p-2 text-gray-600 focus:text-gray-800 hover:text-gray-800 active:text-gray-800 dark:hover:text-gray-300 dark:active:text-gray-300" @click="toggleDarkMode">
        <SunIcon class="w-6 h-6" v-if="isDarkMode" />
        <MoonIcon class="w-6 h-6" v-else />
    </button>
</template>

<script>

    import { MoonIcon, SunIcon } from '@heroicons/vue/24/outline'
    
    export default {
        components: {
            MoonIcon,
            SunIcon,
        },

        computed: {
            isDarkMode() {
                return this.darkMode && this.check;
            },
            check() {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    return true;
                }
                return false;
            }
        },

        data() {
            return {
                darkMode: false
            };
        },

        mounted() {
            // if(this.check && !this.darkMode) {
            //     this.darkMode = this.check;
            // }

            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                this.darkMode = true;
            } else {
                document.documentElement.classList.remove('dark');
                this.darkMode = false;
            }
        },

        methods: {
            toggleDarkMode() {
                this.darkMode = !this.darkMode;

                if(this.darkMode) {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                }else {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                }
            }
        }
    }
</script>