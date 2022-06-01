import axios from 'axios';

class EmployeeService {

    async fetchEmployees(page = 1, size = 10, search = "", sort = {}) {
        const response = await axios.get(`/api/employees`, {
            params: {
                page: page,
                size: size,
                ...(!!search && { search }),
                ...(!!sort && { ...sort }),

            }
        });
        return response.data
    }

    async fetchDepartments() {
        const response = await axios.get(`/api/departments`);
        return response.data
    }

    createEmployee(record) {
        return axios.post('/api/employees', {
            _token: document.querySelector('meta[name="_token"]').getAttribute('content'),
            ...record
        });
    }

    updateEmployee(id, record) {
        return axios.patch(`/api/employees/${id}`, {
            _token: document.querySelector('meta[name="_token"]').getAttribute('content'),
            ...record
        });

    }

    async fetchEmployeeDetails(id) {
        const { data: { employee } } = await axios.get(`/api/employees/${id}`);
        return employee;
    }

    async fetchManagers(department_id) {
        const { data: { managers } } = await axios.get(`/api/employees/managers`, {
            params: {
                department_id
            }
        });
        return managers
    }
}

export default EmployeeService