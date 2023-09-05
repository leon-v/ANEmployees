<template>
    <div id="app">
        <img alt="Vue logo" src="./assets/logo.png">
        <div v-if="currentPage === 'fileUpload'">
            <FileUpload @uploadSuccess="uploadSuccess" msg="Employees" />
        </div>
        <div v-else-if="currentPage === 'employeeTable'">
            <EmployeeTable :employees="employees" @editEmployee="editEmployee" />
        </div>
        <div v-else-if="currentPage === 'editEmployee'">
            <EditEmployee :employee="selectedEmployee" @employeeEdited="employeeEdited" />
        </div>
    </div>
</template>

<script>
import 'bootstrap/dist/css/bootstrap.css';
import FileUpload from './components/FileUpload.vue';
import EmployeeTable from './components/EmployeeTable.vue';
import EditEmployee from './components/EditEmployee.vue';

export default {
    name: 'App',
    components: {
        FileUpload,
        EmployeeTable,
        EditEmployee,
    },
    data() {
        return {
            currentPage: 'fileUpload', // Initially, show the FileUpload component
            employees: [],
            selectedEmployee: null, // Store the selected employee for editing email
        };
    },
    methods: {
        uploadSuccess(uploadedData) {
            this.employees = uploadedData;
            this.currentPage = 'employeeTable';
        },
        editEmployee(employee) {
            this.selectedEmployee = employee;
            this.currentPage = 'editEmployee';
        },
        employeeEdited(){
            this.selectedEmployee = null;
            this.currentPage = 'employeeTable';
        }
    },
};
</script>

<style>
#app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
    margin-top: 60px;
}
</style>