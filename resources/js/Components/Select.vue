<template>
    <select 
        class="border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 rounded-md shadow-sm dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 dark:focus:ring-indigo-600" 
        :class="{
            'border-red-500 dark:border-red-500': hasError,
            'opacity-50 cursor-not-allowed': disabled,
            'bg-gray-200 dark:bg-gray-700 cursor-none touch-none': readonly,
        }"
        :value="modelValue" 
        @change="$emit('update:modelValue', $event.target.value)" 
        :readonly="readonly" 
        :disabled="disabled"
        :aria-disabled="readonly || disabled" 
        ref="select">
        <option value="" v-if="defaultText" disabled>{{ defaultText }}</option>
        <template v-for="(option, idx) in options" :key="idx">
            <option v-if="!option.options" :value="option.value" :disabled="option.disabled">{{ option.text }}</option>
            <optgroup :label="option.text" v-else>
                <option :value="option.value" :disabled="option.disabled">{{ option.text }}</option>
                <option v-for="(suboption, sidx) in option.options" :key="sidx" :value="suboption.value" :disabled="suboption.disabled">{{ suboption.text }}</option>
            </optgroup>
        </template>
    </select>
</template>

<script>
    export default {
        props: {
            readonly: {
                type: Boolean,
                default: () => { return false }
            },
            disabled: {
                type: Boolean,
                default: () => { return false }
            },
            hasError: {
                type: Boolean,
                default: () => { return false }
            },
            defaultText: {},
            modelValue: {},
            options: {
                type: Array,
                default: () => { return [] }
            },
        },

        emits: ['update:modelValue'],

        methods: {
            focus() {
                this.$refs.select.focus()
            }
        }
    }
</script>

