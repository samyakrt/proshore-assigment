<!-- This example requires Tailwind CSS v2.0+ -->
<template>
    <div class="px-4 sm:px-6 lg:px-8 mt-5">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Employees</h1>
            </div>
            <div class="mt-4 mr-8 sm:mt-0 sm:ml-16 sm:flex-none">
                <RouterLink :to="{ name: 'employees.create' }"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                    Add
                    Employee</RouterLink>
            </div>
        </div>
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
                        class="inline-flex items-center px-4 py-2 mr-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Detail
                    </RouterLink>
                    <RouterLink :to="{ name: 'employees.edit', params: { employee: record.id } }"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Edit
                    </RouterLink>
                </td>
            </template>
        </DataTable>
    </div>
</template>

<script>
import EmployeeService from '../../services/EmployeeService'
import DataTable from '../../components/DataTable.vue'
export default {
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
        async fetchEmployeesRecords(page = 1, per_page = 10, search = "", sort = {}) {
            const data = await this.employeeService.fetchEmployees(page, per_page, search, sort)

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