<script type="text/ecmascript-6">
import axios from 'axios';

export default {
    data(){
        return {
            entry: null,
            currentTab: 'form'
        };
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
                            :class="{ active: currentTab == 'arguments' }"
                            href="#"
                            v-on:click.prevent="currentTab = 'arguments'"
                            >Arguments</a
                        >
                    </li>
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            :class="{ active: currentTab == 'options' }"
                            href="#"
                            v-on:click.prevent="currentTab = 'options'"
                            >Options</a
                        >
                    </li>
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            :class="{ active: currentTab == 'form-definition' }"
                            href="#"
                            v-on:click.prevent="currentTab = 'form-definition'"
                            >Form Definition</a
                        >
                    </li>
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            :class="{ active: currentTab == 'form' }"
                            href="#"
                            v-on:click.prevent="currentTab = 'form'"
                            >Form</a
                        >
                    </li>
                </ul>
                <div>
                    <div
                        class="code-bg p-4 mb-0 text-white"
                        v-show="currentTab == 'arguments'"
                    >
                        <vue-json-pretty
                            :data="slotProps.entry.content.arguments"
                        ></vue-json-pretty>
                    </div>
                    <div
                        class="code-bg p-4 mb-0 text-white"
                        v-show="currentTab == 'options'"
                    >
                        <vue-json-pretty
                            :data="slotProps.entry.content.options"
                        ></vue-json-pretty>
                    </div>
                    <div
                        class="code-bg p-4 mb-0 text-white"
                        v-show="currentTab == 'form-definition'"
                    >
                        <vue-json-pretty
                            :data="slotProps.entry.content.form"
                        ></vue-json-pretty>
                    </div>
                    <div class="p-4 mb-0" v-show="currentTab == 'form'">
                        <form>
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
                                        type="text"
                                        :id="field.name"
                                        class="form-control"
                                        :name="field.name"
                                        :value="field.default"
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
                </div>
            </div>
        </div>
    </preview-screen>
</template>
