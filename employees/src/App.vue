<template>
    <div id="app">
        <img alt="Vue logo" src="./assets/logo.png">

        <!-- Main menu component -->
        <main-menu @changePage="changePage" />

        <div v-if="currentPage === 'fileUpload'">
            <FileUpload @uploadSuccess="uploadSuccess" msg="Employees" />
        </div>
        <div v-else-if="currentPage === 'employeeTable'">
            <EmployeeTable @editEmployee="editEmployee" />
        </div>
        <div v-else-if="currentPage === 'companiesTable'">
            <CompaniesTable />
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
import CompaniesTable from './components/CompaniesTable.vue';
import EditEmployee from './components/EditEmployee.vue';
import MainMenu from './components/MainMenu.vue';

export default {
    name: 'App',
    components: {
        FileUpload,
        EmployeeTable,
        CompaniesTable,
        EditEmployee,
        MainMenu,
    },
    data() {
        return {
            currentPage: 'fileUpload', // Initially, show the FileUpload component
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
        },
        changePage(page) {
            this.currentPage = page;
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