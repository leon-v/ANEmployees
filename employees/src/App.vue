<template>
    <div id="app">
        <img alt="Vue logo" src="./assets/logo.png">
        <!-- Conditional rendering based on the uploadSuccess variable -->
        <div v-if="uploadSuccess">
            <!-- Render the EmployeeTable component when upload is successful -->
            <employee-table :employees="employees" />
        </div>
        <div v-else>
            <!-- Render the FileUpload component when upload is not successful -->
            <FileUpload @uploadSuccess="handleUploadSuccess" msg="Employees" />
        </div>
    </div>
</template>

<script>
import 'bootstrap/dist/css/bootstrap.css';
import FileUpload from './components/FileUpload.vue'
import EmployeeTable from './components/EmployeeTable.vue' // Import the EmployeeTable component

export default {
    name: 'App',
    components: {
        FileUpload,
        EmployeeTable // Register the EmployeeTable component
    },
    data() {
        return {
            uploadSuccess: false, // Initially, upload is not successful
            employees: [] // Data to store employees once uploaded
        };
    },
    methods: {
        handleUploadSuccess(uploadedData) {
            // Handle the successful upload and update the state
            this.uploadSuccess = true;
            this.employees = uploadedData; // Assuming uploadedData is an array of employees
        }
    }
}
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
