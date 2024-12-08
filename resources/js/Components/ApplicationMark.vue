<template>
    <AutumIcon v-if="!path"/>
    <div v-else class="flex items-center gap-1">
        <img class="w-10 rounded h-auto" :src="path" :alt="title" />
    </div>
</template>
<script>
    import AutumIcon from '@/Icons/AutumIcon.vue'

    export default {
        components: {
            AutumIcon,
        },
        props: {
            acronym: {
                type: String,
                default: null,
            },
            title: {
                type: String,
                default: 'Autum',
            },
            path: {
                type: String,
            },
        }
    }
</script>