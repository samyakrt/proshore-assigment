<!-- This example requires Tailwind CSS v2.0+ -->
<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="mt-8 flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="flex justify-end">
                        <div class="w-96 mb-3">
                            <input type="text" name="search" id="search" v-model="search"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                placeholder="search" />
                        </div>
                    </div>
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 cursor-pointer capitalize"
                                        v-for="(header, index) in headers" :key="index"
                                        @click.prevent="handleSort(header)">
                                        <a href="#" class="group inline-flex ">
                                            {{ header.name }}
                                            <template v-if="header.sortable">
                                                <span
                                                    class="ml-2 flex-none rounded text-gray-400 group-hover:visible group-focus:visible"
                                                    v-if="header.sort === 'desc'">
                                                    <ChevronDownIcon class="h-5 w-5" aria-hidden="true" />
                                                </span>

                                                <span
                                                    class="ml-2 flex-none rounded text-gray-400 group-hover:visible group-focus:visible"
                                                    v-if="header.sort === 'asc'">
                                                    <ChevronUpIcon class="h-5 w-5" aria-hidden="true" />
                                                </span>

                                            </template>
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="(record, index) in tableRecords" :key="index">
                                    <slot :record="record"></slot>
                                </tr>
                                <tr v-show="!tableRecords.length" :colspan="headers.length">
                                    <slot name="empty">
                                        <span class="text-center">No Records Found</span>
                                    </slot>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <Paginate v-model="page" :pages="paginationDetail.last_page"
                        @update:modelValue="handlePaginationCallback" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ChevronDownIcon, ChevronUpIcon } from '@heroicons/vue/solid'
import debounce from '../utils/debounce'
import Paginate from "@hennge/vue3-pagination";
import "@hennge/vue3-pagination/dist/vue3-pagination.css";

export default {
    components: {
        ChevronDownIcon,
        ChevronUpIcon,
        Paginate
    },
    props: {
        headers: {
            type: Array,
            default: () => ([])
        },
        tableRecords: {
            type: Array,
            default: () => ([])
        },
        paginationDetail: {
            type: Object
        },
        handlePagination: {
            type: Function
        },
        handleSortChange: {
            type: Function
        }
    },
    data() {
        return {
            search: '',
            page: 1,
            per_page: 10,
            search: "",
            errors: [],
        }
    },
    watch: {
        search: debounce(function (val) {
            this.handlePagination(1, this.per_page, val);
        }, 500),
    },
    methods: {
        handlePaginationCallback(page) {
            this.handlePagination(page, this.per_page, this.search)
        },
        handleSearch: debounce(function () {
            this.handlePagination(1, this.per_page, this.search);
        }, 0),
        handleSort(header) {
            let sort = ''

            switch (header.sort) {
                case 'desc':
                    sort = 'asc'
                    break;

                case 'asc':
                    sort = '';
                    break;
                default:
                    sort = 'desc'
            }
            this.handleSortChange(header.name, sort)

            this.handlePagination(this.page, this.per_page, this.search, {
                key: header.name.toLowerCase(),
                order: sort
            })
        }
    }

}
</script>