<script type="text/ecmascript-6">
import _ from 'lodash';
import axios from 'axios';


export default {
    props: {
        resource: {required: true},
        title: {required: true},
        id: {required: true},
        entryPoint: {default: false},
    },


    /**
     * The component's data.
     */
    data() {
        return {
            entry: null,
            ready: false,
        };
    },


    watch: {
        id() {
            this.prepareEntry()
        }
    },


    /**
     * Prepare the component.
     */
    mounted() {
        this.prepareEntry()
    },


    /**
     * Clean after the component is destroyed.
     */
    destroyed() {
        clearTimeout(this.updateEntryTimeout);
    },


    computed: {
        command() {
            return _.find(this.batch, {type: 'command'})
        },

        gravatarUrl() {
            if (this.entry.content.user.email) {
                const md5 = require('md5')
                return 'https://www.gravatar.com/avatar/' + md5(this.entry.content.user.email.toLowerCase()) + '?s=200'
            }
        }
    },


    methods: {
        prepareEntry() {
            document.title = this.title + " - Anvil";
            this.ready = false;

            let unwatch = this.$watch('ready', newVal => {
                if (newVal) {
                    this.$emit('ready');
                    unwatch();
                }
            });

            this.loadEntry((response) => {
                this.entry = response.data.entry;

                this.$parent.entry = response.data.entry;

                this.ready = true;

                this.updateEntry();
            });
        },


        loadEntry(after){
            axios.get(Anvil.basePath + '/anvil-api/' + this.resource + '/' + this.id).then(response => {
                if (_.isFunction(after)) {
                    after(response);
                }
            }).catch(error => {
                this.ready = true;
            })
        },


        /**
         * Update the existing entry if needed.
         */
        updateEntry(){
            if (this.resource != 'jobs') return;
            if (this.entry.content.status !== 'pending') return;

            this.updateEntryTimeout = setTimeout(() => {
                this.loadEntry((response) => {
                    this.entry = response.data.entry;

                    this.$parent.entry = response.data.entry;

                    this.ready = true;
                });

                this.updateEntry();
            }, this.updateEntryTimer);
        }
    }
}
</script>

<template>
    <div>
        <div class="card">
            <div
                class="card-header d-flex align-items-center justify-content-between"
            >
                <h5>{{ this.title }}</h5>
            </div>

            <div
                v-if="!ready"
                class="d-flex align-items-center justify-content-center card-bg-secondary p-5 bottom-radius"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    class="icon spin mr-2"
                >
                    <path
                        d="M12 10a2 2 0 0 1-3.41 1.41A2 2 0 0 1 10 8V0a9.97 9.97 0 0 1 10 10h-8zm7.9 1.41A10 10 0 1 1 8.59.1v2.03a8 8 0 1 0 9.29 9.29h2.02zm-4.07 0a6 6 0 1 1-7.25-7.25v2.1a3.99 3.99 0 0 0-1.4 6.57 4 4 0 0 0 6.56-1.42h2.1z"
                    ></path>
                </svg>

                <span>Fetching...</span>
            </div>

            <div
                v-if="ready && !entry"
                class="d-flex align-items-center justify-content-center card-bg-secondary p-5 bottom-radius"
            >
                <span>No entry found.</span>
            </div>

            <div class="table-responsive">
                <table
                    v-if="ready && entry"
                    class="table mb-0 card-bg-secondary table-borderless"
                >
                    <tbody>
                        <slot name="table-parameters" :entry="entry"></slot>

                        <tr v-if="!entryPoint && command">
                            <td class="table-fit font-weight-bold">Command</td>
                            <td>
                                <router-link
                                    :to="{
                                        name: 'command-preview',
                                        params: { id: command.id }
                                    }"
                                    class="control-action"
                                >
                                    View Command
                                </router-link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <slot
                v-if="ready && entry"
                name="below-table"
                :entry="entry"
            ></slot>
        </div>

        <!-- User Information -->
        <div
            class="card mt-5"
            v-if="ready && entry && entry.content.user && entry.content.user.id"
        >
            <div
                class="card-header d-flex align-items-center justify-content-between"
            >
                <h5>Authenticated User</h5>
            </div>

            <table class="table mb-0 card-bg-secondary table-borderless">
                <tr>
                    <td class="table-fit font-weight-bold">ID</td>

                    <td>
                        {{ entry.content.user.id }}
                    </td>
                </tr>

                <tr v-if="entry.content.user.name">
                    <td class="table-fit font-weight-bold align-middle">
                        Name
                    </td>

                    <td class="align-middle">
                        <img
                            :src="gravatarUrl"
                            class="mr-2 rounded-circle"
                            height="40"
                            width="40"
                            v-if="gravatarUrl"
                        />
                        {{ entry.content.user.name }}
                    </td>
                </tr>

                <tr v-if="entry.content.user.email">
                    <td class="table-fit font-weight-bold">Email Address</td>

                    <td>
                        {{ entry.content.user.email }}
                    </td>
                </tr>
            </table>
        </div>

        <slot
            v-if="ready && entry"
            name="after-attributes-card"
            :entry="entry"
        ></slot>
    </div>
</template>
