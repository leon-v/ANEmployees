<template>
    <div>
        <table class="table">
            <!-- Table headers -->
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Salary</th>
                    <!-- Add other table headers as needed -->
                </tr>
            </thead>
            <!-- Table body with data -->
            <tbody>
                <tr v-for="(employee, index) in employees" :key="index">
                    <td>{{ employee.company }}</td>
                    <td>{{ employee.email }}</td>
                    <td>{{ employee.name }}</td>
                    <td>{{ employee.salary }}</td>
                    <!-- Add other table data as needed -->
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from 'axios'; // Import Axios for making AJAX requests

export default {
    data() {
        return {
            employees: [] // Initialize employees with an empty array
        };
    },
    methods: {
        // Use a method to fetch data asynchronously
        fetchData() {
            const dataUrl = "/php/getEmployees.php"; // Specify the URL for data fetching
            axios.get(dataUrl)
                .then(response => {
                    console.log(response);
                    this.employees = response.data.employees; // Corrected assignment to response.data
                })
                .catch(error => {
                    console.error("Error fetching data:", error); // Log the error
                });
        }
    },
    created() {
        // Fetch data when the component is created
        this.fetchData();
    }
}
</script>

<style scoped>
/* Add any specific table styling here */
</style>
