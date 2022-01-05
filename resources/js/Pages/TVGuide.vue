<template>
    <Head :title="translations.title" />

    <div>
        <v-card
            flat
            :outlined="!$vuetify.breakpoint.xs"
            tile
            width="100%"
            height="100%"
        >
            <v-toolbar height="64" color="grey lighten-4" dense flat>
                <v-toolbar-title v-if="$vuetify.breakpoint.mobile" class="pl-1">
                    {{ translations.title }}
                    {{ date.selected }}
                </v-toolbar-title>
                <v-toolbar-title v-else>{{ translations.title }}</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-select
                    v-show="!$vuetify.breakpoint.smAndDown"
                    :items="category.options"
                    prepend-inner-icon="mdi-filter-variant"
                    :label="translations.category"
                    item-value="id"
                    item-text="name"
                    return-object
                    style="width: 17%"
                    class="mx-4 mt-6"
                    :menu-props="{ bottom: true, offsetY: true }"
                    multiple
                    dense
                    clearable
                    v-model="category.selected"
                >
                    <template #selection="{ index, item }">
                        <v-chip small pill v-if="index <= 1" color="primary"
                        >{{ item.name }}
                        </v-chip>
                        <span v-if="index === 2">(+ {{ category.selected.length - 2 }})</span>
                    </template>
                </v-select>

                <v-btn
                    v-show="$vuetify.breakpoint.smAndDown"
                    color="primary"
                    icon
                    @click="fetch"
                >
                    <v-icon>mdi-filter-variant</v-icon>
                    <span v-if="category.selected.length !== 0">
            ({{ category.selected.length }})
          </span>
                </v-btn>

                <v-btn
                    v-show="$vuetify.breakpoint.mobile"
                    color="primary"
                    icon
                    @click="dateDialog = true"
                ><v-icon>mdi-calendar</v-icon></v-btn
                >
            </v-toolbar>

            <!-- Filter dialog for mobile -->
            <FilterDialog
                :show="filtersDialog"
                @dismiss="dismiss"
                :category.selected="category.selected"
            />

            <v-card-text>
                <div
                    v-if="!$vuetify.breakpoint.mobile"
                    class="d-flex flex-row align-center"
                    :class="$vuetify.breakpoint.smAndDown && mobile"
                >
                    <div class="title mx-3 mb-3">
                        {{ $t("week") }} {{ weekNumber }},
                        {{ format(parseISO(calendarDay), "MMMM yyyy") }}
                        <v-btn color="primary" icon @click="dateDialog = true"
                        ><v-icon>mdi-calendar</v-icon></v-btn
                        >
                    </div>
                </div>

                <DateDialog
                    :display="dateDialog"
                    :calendarDay="calendarDay"
                    @changeWeek="changeWeek"
                />

                <client-only>
                    <v-card flat class="pa-0">
                        <v-row>
                            <v-tabs
                                v-show="!$vuetify.breakpoint.mobile"
                                class="pb-5 px-4"
                                ref="tabs"
                                v-model="tabIndex"
                                background-color="white"
                                ripple
                                center-active
                                :grow="!$vuetify.breakpoint.mobile"
                            >
                                <v-tab
                                    style="width: 20px"
                                    id="dateTabs"
                                    v-for="(date, idx) in dates"
                                    :key="idx"
                                >
                                    {{ toDayDateString(date) }}
                                </v-tab>
                            </v-tabs>
                        </v-row>

                        <v-tabs-items v-model="tabIndex">
                            <v-tab-item v-for="(date, idx) in dates" :key="idx">
                                <v-card v-if="channels.length > 0" flat>
                                    <v-card-text class="pa-0">
                                        <TimelineBoxMobile
                                            v-if="$vuetify.breakpoint.xs"
                                            id="timelineBox"
                                            ref="timelineBox"
                                            :channels="channels"
                                            :direct-link-broadcast="directLinkBroadcast"
                                            @selectChannel="
                        (channel) =>
                          $router.push(localePath(`/channels/${channel.id}`))
                      "
                                        />
                                        <TimelineBox
                                            v-else
                                            id="timelineBox"
                                            ref="timelineBox"
                                            :date="date"
                                            :channels="channels"
                                            :direct-link-broadcast="directLinkBroadcast"
                                            @selectChannel="
                        (channel) =>
                          $router.push(localePath(`/channels/${channel.id}`))
                      "
                                        />
                                    </v-card-text>
                                </v-card>

                                <v-card v-else flat>
                                    <v-card-title v-if="loading" class="justify-center">
                                        <v-progress-circular indeterminate color="primary" />
                                    </v-card-title>
                                    <v-card-title v-else class="justify-center">
                                        {{ $t("noUpcomingBroadcasts") }}
                                    </v-card-title>
                                </v-card>
                            </v-tab-item>
                        </v-tabs-items>
                    </v-card>
                </client-only>
            </v-card-text>
        </v-card>
    </div>
</template>

<script lang="ts">
    import { defineComponent } from 'vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';

    interface MultiSelectable<T> {
        options: Array<T>
        selected: Array<T>
    }

    interface Selectable<T> {
        options: Array<T>
        selected: T
    }

    interface BroadcastCategory {
        name: String
        id: Number
    }

    export default defineComponent({
        components: {
            Head,
            Link,
        },

        props: {
            translations: Object as () => { title: String, category: String },
            date: Object as () => Selectable<String>,
            category: Object as () => MultiSelectable<BroadcastCategory>
        }
    })
</script>
