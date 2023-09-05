<template>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Average Salary</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(company, index) in companies" :key="index">
                    <td>{{ company.name }}</td>
                    <td>{{ formatCurrency(company.averageSalary) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            companies: []
        };
    },
    methods: {
        fetchData() {
            axios.get("/php/getCompanies.php")
                .then(response => {
                    console.log(response);
                    this.companies = response.data.companies; // Corrected assignment to response.data
                })
                .catch(error => {
                    console.error("Error fetching data:", error); // Log the error
                });
        },
        formatCurrency(value) {
            return `$${value.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            })}`;
        },
    },
    created() {
        this.fetchData();
    }
}
</script>