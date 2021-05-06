<template>
    <li>
        <inertia-link
                v-bind="$attrs"
                :class="[
        big ? 'h-11 px-4 flex space-x-3 items-center' : 'space-x-3 sm:space-x-0 h-11 sm:h-16 sm:w-16 px-4 sm:px-none flex sm:justify-center items-center transition',
        'focus:outline-none',
        'hover:bg-primary-800',
        'focus:bg-primary-700',
        {
          'text-white bg-primary-800': isActive,
          'text-primary-300': !isActive,
        },
      ]"
        >
            <slot name="icon"></slot>

            <span class="font-medium text-base">
                <slot></slot>
              </span>
        </inertia-link>
    </li>
</template>

<script>

export default {
    props: {
        isActive: {
            default: false,
            type: Boolean
        },
        big: {
            default: true,
            type: Boolean
        }
    }
}
</script>
