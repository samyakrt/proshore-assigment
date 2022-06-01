<template>
    <DataTable :headers="headers" :tableRecords="tableData" :paginationDetail="paginationDetail"
        :handleSortChange="handleSortChange" :handlePagination="fetchEmployeesRecords">
            <template v-slot:default="{ record }">
                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                    {{ record.name }}
                </td>
                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                    {{ record.address }}
                </td>
                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                    {{ record.department_name }}
                </td>
                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">

                    <RouterLink :to="{ name: 'employees.show', params: { employee: record.id } }"
                        class="inline-flex mr-3 items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Detail
                    </RouterLink>
                    <RouterLink :to="{ name: 'employees.edit', params: { employee: record.id } }"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Edit
                    </RouterLink>
                </td>
            </template>
    </DataTable>
</template>

<script>
import EmployeeService from '../../services/EmployeeService'
import DataTable from '../../components/DataTable.vue'
export default {
    props: {
        employee: {
            type: Object,
            required: true
        }
    },
    components: {
        DataTable
    },
    data() {
        return {
            employeeService: new EmployeeService(),
            tableData: [],
            queries: {

            },
            paginationDetail: {
                current_page: "",
                from: "",
                to: "",
                total: "",
                last_page: 0,
                last_page_url: "",
                search: "",

            },
            headers: [
                {
                    name: 'name',
                    sortable: true,
                    sort: '',
                },
                {
                    name: 'address',
                    sortable: true,
                    sort: ''
                },

                {
                    name: 'department',
                    sortable: true,
                    sort: ''
                },
                {
                    name: 'action',
                    sortable: false,
                    sort: ''
                }
            ]
        }
    },
    mounted() {
        this.fetchEmployeesRecords()
    },
    methods: {
         async fetchEmployeesRecords(page = 1, per_page = 10, search = "", queries = {}) {
            const data = await this.employeeService.fetchEmployees(page, per_page, search, {
                manager_id: this.$route.params.employee,
                ...queries
            })

            const {
                from,
                to,
                total,
                current_page,
                last_page,
                last_page_url,
                search: query,
            } = data;
            this.tableData = data.data;
            this.paginationDetail = {
                from,
                to,
                total,
                current_page,
                last_page,
                last_page_url,
                search: query,
            }
        },

        handleSortChange(header, sort) {
            this.headers = this.headers.map(item => {
                if (item.name === header) {
                    item.sort = sort
                }
                else {
                    item.sort = ''
                }
                return item
            })
        }
    }
}
</script>