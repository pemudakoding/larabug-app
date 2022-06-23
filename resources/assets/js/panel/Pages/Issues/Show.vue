<template>
  <InertiaHead>
    <title>Issues {{ issue.exception }}</title>
  </InertiaHead>

  <div class="flex flex-col space-y-8">
    <Breadcrumbs>
      <BreadcrumbsItem :href="route('panel.issues.index')">Issues</BreadcrumbsItem>
      <BreadcrumbsDivider/>
      <BreadcrumbsItem :href="route('panel.issues.show', issue.id)">{{ issue.exception }}</BreadcrumbsItem>
    </Breadcrumbs>

    <Card>
      <template #header>
        <h2 class="text-xl font-bold">Issue</h2>
      </template>

      <dl class="grid grid-cols-3 gap-px overflow-hidden bg-gray-100 rounded-b-lg">
        <div class="p-6 space-y-1 bg-white">
          <dt class="text-sm font-medium">Exception</dt>
          <dd class="text-base">{{ issue.exception }}</dd>
        </div>

        <div class="p-6 space-y-1 bg-white">
          <dt class="text-sm font-medium">Last occurrence</dt>
          <dd class="text-base">{{ last_occurrence_human }}</dd>
        </div>

        <div class="p-6 space-y-1 bg-white">
          <dt class="text-sm font-medium">Affected versions</dt>
          <dd class="text-base">{{ affected_versions }}</dd>
        </div>

        <div class="p-6 space-y-1 bg-white">
          <dt class="text-sm font-medium">Project</dt>
          <dd class="text-base">{{ project.title }}</dd>
        </div>

        <div class="p-6 space-y-1 bg-white">
          <dt class="text-sm font-medium">Total occurrences</dt>
          <dd class="text-base">{{ total_occurrences }}</dd>
        </div>

        <div class="p-6 space-y-1 bg-white">
          <dt class="text-sm font-medium">New occurrences</dt>
          <dd class="text-base">{{ issue.unread_exceptions_count }}</dd>
        </div>

<!--        <div class="p-6 space-y-1 bg-white">
          <dt class="text-sm font-medium">Application URL</dt>
          <dd class="text-base">
            <a :href="project.url" v-text="project.url" v-if="project.url"></a>
            <badge info v-else>None specified</badge>
          </dd>
        </div>-->

<!--        <div class="p-6 space-y-1 bg-white">
          <dt class="text-sm font-medium">API Key</dt>
          <dd class="text-sm">
            <Code>{{ project.key }}</Code>
          </dd>

          <dd class="pt-3">
            <button @click="refreshToken" class="flex items-center text-sm space-x-2 border-b border-dotted text-primary-500">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                   stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
              <span>Refresh token</span>
            </button>
          </dd>
        </div>-->

<!--        <div class="p-6 space-y-1 bg-white">
          <dt class="text-sm font-medium">New exceptions</dt>
          <dd class="text-base">{{ project.unread_exceptions_count }}</dd>
        </div>

        <div class="p-6 space-y-1 bg-white">
          <dt class="text-sm font-medium">Total exceptions</dt>
          <dd class="text-base">{{ project.total_exceptions }}</dd>
        </div>

        <div class="p-6 space-y-1 bg-white">
          <dt class="text-sm font-medium">Description</dt>
          <dd class="text-base" v-if="project.description">{{ project.description }}</dd>
          <dd class="text-base" v-else>-No description-</dd>
        </div>-->
      </dl>
    </Card>

    <Card>
      <template #buttonheader>
        <h2 class="text-xl font-bold">Occurrences</h2>
      </template>

      <ul class="divide-y divide-gray-200">
        <li v-for="exception in exceptions.data" :key="exception.id">
          <div :href="route('panel.exceptions.show', {id: issue.project_id, exception: exception })"
               class="flex items-center px-6 py-4 space-x-6 hover:bg-gray-100">
            <inertia-link class="flex flex-1 items-center"
                          :href="route('panel.exceptions.show', {id: issue.project_id, exception: exception })">
              <div class="flex-1">
                <p class="font-medium text-bold"
                   v-bind:class="{'text-gray-500': exception.status === 'FIXED'}">
                </p>

                <p class="text-sm text-gray-600">
                  <Badge success v-if="exception.status === 'FIXED'">{{
                      exception.status_text
                    }}
                  </Badge>
                  <Badge info v-if="exception.status === 'READ'">{{ exception.status_text }}</Badge>
                  <Badge danger v-if="exception.status === 'OPEN'">{{ exception.status_text }}</Badge>
                  <span v-if="exception.snooze_until">&centerdot; </span>
                  <Badge info v-if="exception.snooze_until">Snoozed until {{
                      exception.snooze_until
                    }}
                  </Badge>
                  &centerdot; {{ exception.human_date }} &centerdot;
                  {{ exception.created_at }}
                  <Badge info v-if="exception.file_type === 'javascript'">&centerdot; Javascript
                  </Badge>
                </p>
              </div>
              <div class="flex-1"></div>

              <span v-if="exception.project_version">
                <Badge gray big>{{
                  exception.project_version
                }}</Badge>
              </span>

              <span v-if="exception.environment">
                <Badge gray big>{{
                  exception.environment
                }}</Badge>
              </span>

              <svg
                  class="w-6 h-6 text-gray-500"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
              >
                <path
                    fill-rule="evenodd"
                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                    clip-rule="evenodd"
                ></path>
              </svg>
            </inertia-link>
          </div>
        </li>
      </ul>

      <template #footer>
        <Paginator :paginated="exceptions"/>
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
import Badge from '@/Components/Badge'
import Button from '@/Components/Button'
import ButtonRack from '@/Components/ButtonRack'
import ButtonRackItem from '@/Components/ButtonRackItem'
import Code from '@/Components/Code'
import Paginator from '@/Components/Paginator'
import EditProject from '@/Partials/EditProject'

export default {
  layout: AppLayout,
  components: {
    EditProject,
    Breadcrumbs,
    BreadcrumbsItem,
    BreadcrumbsDivider,
    Code,
    Card,
    Button,
    ButtonRack,
    ButtonRackItem,
    Paginator,
    Badge,
  },
  props: {
    issue: Object,
    exceptions: Object,
    project: Object,
    filters: Object,
    affected_versions: Array,
    last_occurrence_human: String,
    total_occurrences: Number,
  },
  data() {
    return {
      sending: false,

      selected: [],

      form: {
        issue: this.issue.id,
        status: this.filters.status,
        has_feedback: this.filters.has_feedback
      },
    }
  },
}
</script>
