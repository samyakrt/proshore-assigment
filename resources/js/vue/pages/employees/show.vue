<template>
    <div class="hidden sm:block">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a v-for="(tab, index) in activeTabs" :key="tab.name" :href="tab.href"
                    @click.prevent="changeTab(index, tab.name)"
                    :class="[tab.current ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm']"
                    :aria-current="tab.current ? 'page' : undefined">
                    <component :is="tab.icon"
                        :class="[tab.current ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500', '-ml-0.5 mr-2 h-5 w-5']"
                        aria-hidden="true" />
                    <span>{{ tab.name }}</span>
                </a>
            </nav>
            <div>

                <component :is="activeTab" :employee="employee"></component>

            </div>
        </div>
    </div>
</template>

<script>
import EmployeeService from '../../services/EmployeeService'
import Profile from '../../components/employee/profile.vue'
import EmployeeList from '../../components/employee/employee-list.vue'

export default {
    data() {
        return {
            employee_service: new EmployeeService(),
            employee: {
                name: '',
                address: '',
                department: '',
            },
            is_loading: true,
            has_record: true,
            tabs: [
                { name: 'Profile', href: '#', current: true, show: true },
                { name: 'EmployeeList', href: '#', current: false, show: true },
            ],
            activeTab: 'Profile',
        }
    },
    components: {
        EmployeeList, Profile
    },
    mounted() {
        this.fetchEmployeeRecords()
    },
    computed: {
        activeTabs() {
            return this.tabs.filter(tab => tab.show)
        },
    },
    watch: {
        $route() {

            this.fetchEmployeeRecords()
            this.tabs = this.tabs.map((tab, index) => {
                if (index == 0) {
                    tab.current = true
                } else {
                    tab.current = false
                }

                return tab
            })
            this.activeTab = 'Profile'
        }
    },
    methods: {
        async fetchEmployeeRecords() {
            try {
                const employee = await this.employee_service.fetchEmployeeDetails(this.$route.params.employee)
                this.employee = employee
                this.is_loading = false

                if (employee.role_id !== 1) {
                    this.tabs[1].show = false
                }
            } catch (error) {
                this.has_record = false
            }
        },
        changeTab(tab_index, name) {
            this.activeTab = name;
            this.tabs = this.tabs.map((tab, index) => {
                if (index === tab_index) {
                    tab.current = tab_index === index
                }
                else {
                    tab.current = false
                }
                return tab
            })
        }
    }
}
</script>