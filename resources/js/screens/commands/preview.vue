<script type="text/ecmascript-6">
import axios from 'axios';
import { Terminal } from 'xterm';
import { AttachAddon } from 'xterm-addon-attach';

export default {
    data() {
        return {
            entry: null,
            form: {},
            currentTab: 'terminal'
        };
    },
    mounted() {
        setTimeout(() => {
            let terminal = new Terminal();
            let channel = 'command.' + this.entry.content.command;
            console.log('Listening to channel ' + channel);

            window.Echo
                .private(channel)
                .listen('.Datashaman\\Anvil\\AnvilOutput', (e) => {
                    console.log('Received', e);
                    terminal.write(e.message + (e.newline ? "\r\n" : ''));
                });

            terminal.open(this.$refs.terminal);
            terminal.resize(80, 25);
            terminal.clear();
        }, 250);
    },
    methods: {
        handleSubmit(evt) {
            let url = window.Anvil.routes.runs_store.replace('%command%', this.entry.content.command);

            axios({
                method: 'post',
                url: url,
                data: this.form
            })
            .then((response) => {
                this.currentTab = 'terminal';
            })
            .catch(console.error);
        }
    }
}
</script>

<template>
    <preview-screen
        title="Command Details"
        resource="commands"
        :id="$route.params.id"
        entry-point="true"
    >
        <template slot="table-parameters" slot-scope="slotProps">
            <tr>
                <td class="table-fit font-weight-bold">Command</td>
                <td>
                    {{ slotProps.entry.content.command }}
                </td>
            </tr>
            <tr v-if="slotProps.entry.content.description">
                <td class="table-fit font-weight-bold">Description</td>
                <td>
                    {{ slotProps.entry.content.description }}
                </td>
            </tr>
            <tr v-if="slotProps.entry.content.help">
                <td class="table-fit font-weight-bold">Help</td>
                <td>
                    {{ slotProps.entry.content.help }}
                </td>
            </tr>
            <tr v-if="slotProps.entry.content.synopsis">
                <td class="table-fit font-weight-bold">Synopsis</td>
                <td>
                    {{ slotProps.entry.content.synopsis }}
                </td>
            </tr>
        </template>

        <div slot="after-attributes-card" slot-scope="slotProps">
            <div class="card mt-5">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            :class="{ active: currentTab == 'form' }"
                            href="#"
                            v-on:click.prevent="currentTab = 'form'"
                            >Form</a
                        >
                    </li>
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            :class="{ active: currentTab == 'terminal' }"
                            href="#"
                            v-on:click.prevent="currentTab = 'terminal'"
                            >Terminal</a
                        >
                    </li>
                </ul>
                <div>
                    <div class="p-4 mb-0" v-show="currentTab == 'form'">
                        <form @submit.prevent="handleSubmit">
                            <template
                                v-for="field in slotProps.entry.content.form"
                            >
                                <div
                                    v-if="
                                        field.type == 'argument' ||
                                            field.acceptValue
                                    "
                                    class="form-group"
                                >
                                    <label :for="field.name">{{
                                        field.label
                                    }}</label>
                                    <input
                                        v-model="form[field.name]"
                                        type="text"
                                        :id="field.name"
                                        class="form-control"
                                        :name="field.name"
                                        :required="field.required"
                                    />
                                    <small
                                        v-if="field.description"
                                        class="form-text text-muted"
                                        >{{ field.description }}</small
                                    >
                                </div>
                                <div
                                    v-if="
                                        field.type == 'option' &&
                                            !field.acceptValue
                                    "
                                    class="form-check"
                                >
                                    <input
                                        v-model="form[field.name]"
                                        type="checkbox"
                                        :id="field.name"
                                        class="form-check-input"
                                    />
                                    <label
                                        :for="field.name"
                                        class="form-check-label"
                                        >{{ field.label }}</label
                                    >
                                    <small
                                        v-if="field.description"
                                        class="form-text text-muted"
                                        >{{ field.description }}</small
                                    >
                                </div>
                            </template>

                            <button type="submit" class="btn btn-primary">
                                Run
                            </button>
                        </form>
                    </div>
                    <div class="p-4" v-show="currentTab == 'terminal'">
                        <div ref="terminal"></div>
                    </div>
                </div>
            </div>
        </div>
    </preview-screen>
</template>
