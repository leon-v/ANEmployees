<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <form @submit.prevent="uploadFile" class="mb-3">
                    <div class="mb-3">
                        <label for="fileInput" class="form-label">Select a CSV file</label>
                        <input type="file" class="form-control" id="fileInput" @change="onFileChange" accept=".csv" />
                    </div>
                    <button type="submit" class="btn btn-primary">Upload CSV</button>
                </form>
                <button @click="showTable" class="btn btn-primary">View Data</button><br><br>
                <a href="/Employees.csv">Download Sample CSV File</a>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            selectedFile: null,
        };
    },
    methods: {
        onFileChange(event) {
            this.selectedFile = event.target.files[0];
        },
        uploadFile() {
            if (!this.selectedFile) {
                alert("Please select a CSV file.");
                return;
            }

            // Create a FormData object to send the file
            const formData = new FormData();
            formData.append('csvFile', this.selectedFile);

            // Make an HTTP POST request to the PHP script
            axios
                .post('/php/FileUpload.php', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                })
                .then((response) => {
                    if (response.headers['content-type'] && response.headers['content-type'].includes('application/json')) {
                        this.$emit('uploadSuccess');
                    } else {
                        alert('Invalid response format. Expected JSON. Response body: ' + response.data);
                    }
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
        showTable() {
            this.$emit('uploadSuccess');
        },
    },

};
</script>