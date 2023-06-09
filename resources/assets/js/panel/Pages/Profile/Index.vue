<template>
    <InertiaHead title="Profile" />

    <div class="flex flex-col space-y-8">
        <Breadcrumbs>
            <BreadcrumbsItem href="/panel/profile">Profile settings</BreadcrumbsItem>
        </Breadcrumbs>

        <Card contained>
            <template #header>
                <h2 class="text-xl font-bold">Profile</h2>
            </template>

            <form class="space-y-6" action="">
                <FormInputGroup
                    v-model="form.name"
                    :error="form.errors.name"
                    label="Name"
                    id="name"
                    required
                />

                <FormInputGroup
                    v-model="form.email"
                    :error="form.errors.email"
                    type="email"
                    label="E-mail"
                    id="email"
                    required
                />
            </form>

            <template #footer>
                <div class="flex items-center space-x-3">
                    <Button @click="update" primary :disabled="form.processing">Save profile</Button>
                </div>
            </template>
        </Card>

        <Card contained>
          <template #header>
            <h2 class="text-xl font-bold">Password</h2>
          </template>

          <form class="space-y-6" action="">
            <FormInputGroup
                v-model="password.current"
                :error="password.errors.current"
                type="password"
                label="Current Password"
                id="current_password"
                required
            />

            <FormInputGroup
                v-model="password.password"
                :error="password.errors.password"
                type="password"
                label="New Password"
                id="new_password"
                required
            />

            <FormInputGroup
                v-model="password.password_confirmation"
                :error="password.errors.password_confirmation"
                type="password"
                label="Confirm New Password"
                id="confirm_new_password"
                required
            />
          </form>

          <template #footer>
            <div class="flex items-center space-x-3">
              <Button @click="changePassword" primary :disabled="password.processing">Change password</Button>
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

                <h3 class="text-base font-bold">Email SMTP</h3>

                <div class="flex items-center space-x-2">
                    <input
                        :class="[
                          'text-primary-600 rounded border-gray-300 transition',
                          'focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-offset-0',
                        ]"
                        id="custom_mailer"
                        type="checkbox"
                        v-model="form.mailer.use_mailer"
                    />

                    <label class="text-sm font-medium" for="custom_mailer">
                        Use custom STMP mailer
                    </label>
                </div>

                <div v-if="form.mailer.use_mailer" class="space-y-6">
                    <FormInputGroup
                        v-model="form.mailer.host"
                        :error="form.errors.mailer_host"
                        label="Host"
                        id="email_host"
                        :required="form.mailer.use_mailer"
                    />

                    <FormInputGroup
                        v-model="form.mailer.port"
                        :error="form.errors.mailer_port"
                        label="Port"
                        id="email_port"
                        :required="form.mailer.use_mailer"
                    />

                    <FormInputGroup
                        v-model="form.mailer.username"
                        :error="form.errors.mailer_username"
                        label="Username"
                        id="email_username"
                        :required="form.mailer.use_mailer"
                    />

                    <FormInputGroup
                        v-model="form.mailer.password"
                        :error="form.errors.mailer_password"
                        label="Password"
                        id="email_password"
                        :required="form.mailer.use_mailer"
                    />

                    <FormInputGroup
                        v-model="form.mailer.mail_encryption"
                        :error="form.errors.mailer_encryption"
                        label="Encryption"
                        id="email_encryption"
                        :required="form.mailer.use_mailer"
                    />

                    <FormInputGroup
                        v-model="form.mailer.from_name"
                        :error="form.errors.mailer_from_name"
                        label="From name"
                        id="email_from_name"
                        :required="form.mailer.use_mailer"
                    />

                    <FormInputGroup
                        v-model="form.mailer.from_email"
                        :error="form.errors.mailer_from_email"
                        label="From email"
                        id="email_from_email"
                        :required="form.mailer.use_mailer"
                    />
                </div>
            </form>

            <template #footer>
                <div class="flex items-center space-x-3">
                    <Button @click="updateSettings" primary :disabled="form.processing">Save preferences</Button>
                </div>
            </template>
        </Card>

        <Card contained>
            <template #header>
                <h2 class="text-xl font-bold">Mobile devices</h2>
            </template>

            <form class="space-y-6" action="">
                <h3 class="text-base font-bold">Manage devices</h3>

                <div class="flex items-center space-x-2">
                    <Button as="inertia-link" :href="route('panel.profile.fcm-tokens.index')" primary>Manage mobile devices</Button>
                </div>
            </form>
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
import { useToast } from "vue-toastification";
import { useForm } from '@inertiajs/inertia-vue3'

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
            form: useForm ({
                name: this.user.name,
                email: this.user.email,
                password: this.user.password,
                newsletter: this.user.newsletter,
                settings: this.settings,
                mailer: {
                    use_mailer: this.mailer.use_mailer,
                    mailer: this.mailer.mailer,
                    host: this.mailer.host,
                    port: this.mailer.port,
                    username: this.mailer.username,
                    password: this.mailer.password,
                    mail_encryption: this.mailer.mail_encryption,
                    from_name: this.mailer.from_name,
                    from_email: this.mailer.from_email,
                },
            }, {
              key: 'form'
            }),
            password: useForm ({
              current: null,
              password: null,
              password_confirmation: null
            }, {
              key: 'password',
              remember: false,
            }),
        }
    },
    props: {
        user: Object,
        settings: Object,
        mailer: Object,
    },
    methods: {
        update() {
            this.form.patch(this.route('panel.profile.update'), {
                onStart: () => this.sending = true,
                onFinish: () => this.sending = false
            });
        },
        changePassword() {
            this.password.patch(this.route('panel.profile.changePassword'), {
                onStart: () => this.sending = true,
                onSuccess: () => this.password.reset(),
                onFinish: () => {
                    this.sending = false
                    this.password.reset()
                }
            });
        },
        updateSettings() {
            this.sending = true

            this.form.patch(this.route('panel.profile.settings'), {
                onFinish: () => this.sending = false
            })
        }
    },

    setup() {
        const toast = useToast();
        return { toast }
    },
}
</script>
