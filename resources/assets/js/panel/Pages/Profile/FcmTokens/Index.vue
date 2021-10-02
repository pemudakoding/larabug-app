<template>
    <div class="flex flex-col space-y-8">
        <Breadcrumbs>
            <BreadcrumbsItem href="/panel/profile">Profile settings</BreadcrumbsItem>
            <BreadcrumbsItem>/</BreadcrumbsItem>
            <BreadcrumbsItem href="/panel/profile/fcm-tokens">Mobile devices</BreadcrumbsItem>
        </Breadcrumbs>

        <Card contained>
            <template #header>
                <h2 class="text-xl font-bold">Devices</h2>
            </template>

            <div class="overflow-x-auto">
                <div class="inline-block min-w-full px-4">
                    <div class="overflow-hidden bg-white shadow rounded-lg">
                        <table class="w-full text-left divide-y table-auto">
                            <thead>
                            <tr class="divide-x bg-gray-50">
                                <th class="px-4 py-2 text-sm font-semibold text-gray-600">
                                    Token
                                </th>

                                <th class="px-4 py-2 text-sm font-semibold text-gray-600">
                                    Created
                                </th>
                                <th class="px-4 py-2 text-sm font-semibold text-gray-600">

                                </th>
                            </tr>
                            </thead>

                            <tbody class="divide-y whitespace-nowrap">
                            <tr class="divide-x" v-for="token in tokens">
                                <td class="px-4 py-3" v-if="token.device">
                                    {{ token.device.name }}
                                </td>
                                <td class="px-4 py-3" v-else>Device</td>

                                <td class="px-4 py-3">{{ token.created_at }}</td>
                                <td class="px-4 py-3">
                                    <Button danger @click="deleteDevice(token)">Delete</Button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </Card>
    </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'

import Breadcrumbs from '@/Components/Breadcrumbs'
import BreadcrumbsItem from '@/Components/BreadcrumbsItem'
import BreadcrumbsDivider from '@/Components/BreadcrumbsDivider'
import Card from '@/Components/Card'
import Button from '@/Components/Button'
import FormInputGroup from '@/Components/FormInputGroup'
import FormTextareaGroup from '@/Components/FormTextareaGroup'
import Checkbox from '@/Components/Checkbox'
import {useToast} from "vue-toastification";

export default {
    layout: AppLayout,
    components: {
        Breadcrumbs,
        BreadcrumbsItem,
        BreadcrumbsDivider,
        Card,
        Button,
        FormInputGroup,
        FormTextareaGroup,
        Checkbox,
    },
    data() {
        return {}
    },
    props: {
        tokens: Array,
    },
    methods: {
        deleteDevice(token) {
            this.$inertia.delete(this.route('panel.profile.fcm-tokens.destroy', token.id))
        }
    },

    setup() {
        const toast = useToast();
        return {toast}
    },
}
</script>
