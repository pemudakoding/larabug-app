<template>
    <InertiaHead title="Profile" />

    <div class="flex flex-col space-y-8">
        <Breadcrumbs>
            <BreadcrumbsItem href="/profile">Profile settings</BreadcrumbsItem>
        </Breadcrumbs>

        <Card contained>
            <template #header>
                <h2 class="text-xl font-bold">Profile</h2>
            </template>

            <form class="space-y-6" action="">
                <FormInputGroup v-model="form.name" label="Name" id="name" required/>

                <FormInputGroup v-model="form.email" type="email" label="E-mail" id="email" required/>
            </form>

            <template #footer>
                <div class="flex items-center space-x-3">
                    <Button @click="update" primary>Save profile</Button>
                </div>
            </template>
        </Card>

        <Card contained>
          <template #header>
            <h2 class="text-xl font-bold">Password</h2>
          </template>

          <form class="space-y-6" action="">
            <FormInputGroup v-model="password.current" type="password" label="Current Password" id="current_password" required/>

            <FormInputGroup v-model="password.password" type="password" label="New Password" id="new_password" required/>

            <FormInputGroup v-model="password.password_confirmation" type="password" label="Confirm New Password" id="confirm_new_password" required/>
          </form>

          <template #footer>
            <div class="flex items-center space-x-3">
              <Button @click="changePassword" primary>Change password</Button>
            </div>
          </template>
        </Card>

        <Card contained>
            <template #header>
                <h2 class="text-xl font-bold">Preferences</h2>
            </template>

            <form class="space-y-6" action="">
                <h3 class="text-base font-bold">General</h3>

                <div class="flex items-center space-x-2">
                    <input
                            :class="[
        'text-primary-600 rounded border-gray-300 transition',
        'focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-offset-0',
      ]"
                            id="newsletter"
                            type="checkbox"
                            v-model="form.newsletter"
                    />

                    <label class="text-sm font-medium" for="newsletter">
                        Receive newsletter
                    </label>
                </div>
            </form>

            <template #footer>
                <div class="flex items-center space-x-3">
                    <Button @click="updateSettings" primary>Save preferences</Button>
                </div>
            </template>
        </Card>

        <div>
            <Button secondary @click="logout">Logout</Button>
        </div>
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
import { useToast } from "vue-toastification";

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
        return {
            sending: false,
            form: {
                name: this.user.name,
                email: this.user.email,
                password: this.user.password,
                newsletter: this.user.newsletter,
                settings: this.settings
            },
            password: {
              current: null,
              password: null,
              password_confirmation: null
            },
        }
    },
    props: {
        user: Object,
        settings: Object
    },
    methods: {
        update() {
            this.$inertia.patch(this.route('panel.profile.update'), this.form, {
                onStart: () => this.sending = true,
                onFinish: () => {
                    this.sending = false;
                }
            });
        },
        changePassword() {
            this.$inertia.patch(this.route('panel.profile.changePassword'), this.password, {
                onStart: () => this.sending = true,
                onFinish: () => {
                    this.sending = false;
                    this.password = {
                        current: null,
                        password: null,
                        password_confirmation: null
                    };
                }
            });
        },
        updateSettings() {
            this.sending = true

            this.$inertia.patch(this.route('panel.profile.settings'), {
                settings: this.form.settings,
                newsletter: this.form.newsletter
            })
                .then(() => {
                    this.sending = false
                })
        },

        logout() {
            this.$inertia.post(this.route('logout'));
        }
    },

    setup() {
        const toast = useToast();
        return { toast }
    },
}
</script>
