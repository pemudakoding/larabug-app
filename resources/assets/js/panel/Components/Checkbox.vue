<template>
    <div class="flex items-center space-x-2">
        <input
                v-bind="$attrs"
                :class="[
        'text-primary-600 rounded border-gray-300 transition',
        'focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-offset-0',
      ]"
                type="checkbox"
                :value="modelValue"
                :checked="modelValue"
                @change="$emit('update:modelValue', $event.target.value)"
        />

        <label class="text-sm font-medium" :for="$attrs.id">
            {{ label }}
        </label>
    </div>
</template>

<script>
export default {
    inheritAttrs: false,
    props: {
        label: {
            type: String,
            required: true,
        },
        modelValue: {
            required: true,
        },
    },
}
</script>
