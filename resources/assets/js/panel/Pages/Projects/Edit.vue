<template>
    <InertiaHead>
      <title>Edit {{ project.title }} Project</title>
    </InertiaHead>

    <div class="flex flex-col space-y-8">
        <Breadcrumbs>
            <BreadcrumbsItem :href="route('panel.projects.index')">Projects</BreadcrumbsItem>
            <BreadcrumbsDivider/>
            <BreadcrumbsItem :href="route('panel.projects.show', project.id)">{{ project.title }}</BreadcrumbsItem>
            <BreadcrumbsDivider/>
            <BreadcrumbsItem :href="route('panel.projects.edit', project.id)">Edit</BreadcrumbsItem>
        </Breadcrumbs>

        <Card contained>
            <template #header>
                <h2 class="text-xl font-bold">Edit project</h2>
            </template>

            <form class="space-y-6" @submit.prevent="submit">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <FormInputGroup
                            v-model="form.title"
                            :error="form.errors.title"
                            helper-text="The project's title"
                            label="Title"
                            id="title"
                            required
                    />
                    <FormInputGroup
                        v-model="form.url"
                        :error="form.errors.url"
                        label="App URL"
                        id="app_url"
                        required
                    />

                    <FormTextareaGroup v-model="form.description" label="Description" id="description"/>
                </div>


                <div class="sm:col-span-6 border-t pt-4">
                    <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                        <div class="ml-4 mt-2">
                            <h2 class="text-xl font-medium text-blue-gray-900">Notifications</h2>
                            <p class="mt-1 text-sm text-blue-gray-500">Here you can change the notification settings for this project.</p>
                        </div>
                        <div class="ml-4 mt-2 flex-shrink-0">
                            <Switch v-model="form.notifications_disabled" :class="[!form.notifications_disabled ? 'bg-primary-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-200']">
                                <span class="sr-only">Notifications Disabled</span>
                                <span aria-hidden="true" :class="[!form.notifications_disabled ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']" />
                            </Switch>
                        </div>
                    </div>
                </div>


                <div class="mt-4 space-y-4" :class="{'opacity-25': form.notifications_disabled}">
                    <div class="relative flex items-start">
                        <div class="flex items-center h-5">
                            <input
                                :class="[
        'text-primary-600 rounded border-gray-300 transition',
        'focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-offset-0',
      ]"
                                id="receive_email"
                                type="checkbox"
                                v-model="form.receive_email" />
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="receive_email" class="font-medium text-gray-700">Receive email on new exceptions</label>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4" :class="{'opacity-25': form.notifications_disabled}">
                    <FormInputGroup
                        v-model="form.slack_webhook"
                        :error="form.errors.slack_webhook"
                        label="Slack Webhook URL"
                        id="slack_webhook_url"
                        required
                    />
                    <FormInputGroup
                            v-model="form.discord_webhook"
                            :error="form.errors.discord_webhook"
                            label="Discord Webhook URL"
                            id="discord_webhook_url"
                            required
                    />

                    <FormInputGroup
                        v-model="form.custom_webhook"
                        :error="form.errors.custom_webhook"
                        label="Custom Webhook URL"
                        id="custom_webhook_url"
                        required
                    />
                </div>
            </form>

            <template #footer>
                <div class="flex items-center space-x-3">
                    <Button @click="submit" primary>Update project</Button>
                    <Button as="inertia-link" :href="route('panel.projects.show', project.id)" secondary>Cancel</Button>
                    <Button @click="destroy" danger>Delete</Button>
                </div>
            </template>
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
import { useForm } from '@inertiajs/inertia-vue3'
import { Switch } from '@headlessui/vue'

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
        Switch,
    },

    props: {
        project: Object
    },

    data() {
        return {
            sending: false,
            form: useForm ({
                title: this.project.title,
                url: this.project.url,
                description: this.project.description,
                receive_email: this.project.receive_email,
                slack_webhook: this.project.slack_webhook,
                discord_webhook: this.project.discord_webhook,
                custom_webhook: this.project.custom_webhook,
                notifications_disabled: this.project.notifications_disabled,
            }),
        }
    },

    methods: {
        submit() {
            this.form.put(this.route('panel.projects.update', this.project.id), this.form, {
                onStart: () => this.sending = true,
                onFinish: () => this.sending = false
            })
        },
        destroy() {
            this.form.delete(this.route('panel.projects.destroy', this.project.id),{
              onBefore: () => confirm('Are you sure you want to delete this project?'),
            })
        },
    },
}
</script>
