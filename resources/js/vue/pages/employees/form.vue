<template>
    <notifications position="top right" classes="rounded-md bg-green-50 p-4" />
    <form class="container shadow sm:rounded-md sm:overflow-hidden" :style="{ 'width': '50%', 'margin': '0 auto' }"
        @submit.prevent="submitForm">
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ is_edit ? 'Edit' : 'Create' }} Employee</h3>
            </div>
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-3">
                    <label for="name" class="block text-sm font-medium text-gray-700">name</label>
                    <input type="text" name="test" id="test" v-model="employee['name']"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    <div v-if="errors['name']" :class="errorTextStyle">
                        {{ errors['name'][0] }}
                    </div>
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label for="address" class="block text-sm font-medium text-gray-700">address</label>
                    <input type="text" name="address" id="address" v-model="employee['address']"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    <div v-if="errors['address']" :class="errorTextStyle">
                        {{ errors['address'][0] }}
                    </div>
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select id="role" name="role" v-model="employee['role_id']"
                        class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="" hidden>Select Role</option>
                        <option value="1">Manager</option>
                        <option value="2">Employee</option>
                    </select>
                    <div v-if="errors['role_id']" :class="errorTextStyle">
                        {{ errors['role_id'][0] }}
                    </div>
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label for="department" class="block text-sm font-medium text-gray-700">department</label>
                    <select id="department" name="department" v-model="employee['department_id']"
                        class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="" hidden>Select Department</option>
                        <option v-for="(department, index) in departments" :key="index" :value="department.id">
                            {{ department.name }}
                        </option>
                    </select>
                    <div v-if="errors['department_id']" :class="errorTextStyle">
                        {{ errors['department_id'][0] }}
                    </div>
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label for="department" class="block text-sm font-medium text-gray-700">Manager</label>
                    <select id="department" name="department" v-model="employee['manager_id']"
                        class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="" hidden>Select Manager</option>
                        <option v-for="(manager, index) in managers" :key="index" :value="manager.id">
                            {{ manager.name }}
                        </option>
                    </select>
                    <div v-if="errors['manager_id']" :class="errorTextStyle">
                        {{ errors['manager_id'][0] }}
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-4">
                    <label for="join_date" class="block text-sm font-medium text-gray-700">Join Date</label>
                    <input type="date" name="join_date" id="join_date" v-model="employee['join_date']"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    <div v-if="errors['join_date']" :class="errorTextStyle">
                        {{ errors['join_date'][0] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button type="submit"
                class="bg-gray-100 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save</button>
        </div>
    </form>
</template>

<script>
import EmployeeService from '../../services/EmployeeService'
import "vue-toastification/dist/index.css";
import { useToast } from "vue-toastification";

const employee_service = new EmployeeService()
export default {
    data() {
        return {
            departments: [],
            toast: useToast(),
            is_loading: true,
            errors: {},
            managers: [],
            employee: {
                name: '',
                address: '',
                role_id: '',
                department_id: '',
                join_date: '',
                manager_id: ''
            },
            is_edit: false,
            employee_id: '',
            errorTextStyle: 'block text-sm font-medium text-red-700'
        }
    },

    watch: {
        'employee.department_id': function (new_id) {
            this.manager_id = ''
            this.fetchManagers(new_id)
        },
        'employee.role_id': function (id) {
            if (id == 1) {
                this.employee.manager_id = ''
            }
        }
    },
    async mounted() {
        this.setDepartments()
        const employee_id = this.$route.params.employee
        if (employee_id) {
            this.employee_id = employee_id
            this.is_edit = true
            const employee = await employee_service.fetchEmployeeDetails(employee_id)
            this.employee = employee;
        }
    },
    methods: {
        async setDepartments() {
            const departments = await employee_service.fetchDepartments()
            this.departments = [
                ...this.departments,
                ...departments
            ];
            this.is_loading = false;
        },
        async submitForm() {
            try {

                if (this.employee_id) {
                    await employee_service.updateEmployee(this.employee_id, this.employee)
                    this.toast.success("Employee Updated Successfully")
                }
                else {
                    await employee_service.createEmployee(this.employee)
                    this.toast.success("Employee Created Successfully")
                }
                this.$router.push({
                    name: 'employees.index',
                })

            } catch (error) {
                
                if (error.response && error.response.status == 422) {
                    this.toast.error("Validation Error")
                    this.errors = error.response.data.errors;
                }
            }

        },
        async fetchManagers(department_id) {
            const managers = await employee_service.fetchManagers(department_id)
            this.managers = managers
        },
    }
}
</script>