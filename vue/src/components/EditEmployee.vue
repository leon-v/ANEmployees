<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2>Edit Employee</h2>
                <form @submit.prevent="editEmployee">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" v-model="name" class="form-control" id="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" v-model="email" class="form-control" id="email" required>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        employee: Object, // Pass the employee object as a prop to edit their email
    },
    data() {
        return {
            employeeId: this.employee.employeeId,
            email: this.employee.email,
            name: this.employee.name,
        };
    },
    methods: {
        editEmployee() {
            // Create an object with the updated data
            const updatedEmployeeData = {
                employeeId: this.employeeId,
                email: this.email,
                name: this.name,
            };

            // Send an AJAX request to update the employee data
            axios
                .put('/php/updateEmployee.php', updatedEmployeeData)
                .then((response) => {
                    console.log(response);
                    this.$emit("employeeEdited");
                })
                .catch((error) => {
                    console.error('File upload error:', error);

                    if (error.response && error.response.data && error.response.data.message) {
                        alert('Error: ' + error.response.data.message);
                    } else {
                        alert('An error occurred while uploading the file: ');
                    }
                });
        },
    },
};
</script>