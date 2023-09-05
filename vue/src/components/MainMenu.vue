<template>
    <div>
        <div class="btn-toolbar justify-content-center" role="toolbar">
            <div class="btn-group mr-2" role="group">
                <button @click="setCurrentPage('employeeTable')" class="btn btn-primary"
                    :class="{ active: currentPage === 'employeeTable' }">
                    Employees
                </button>
            </div>
            <div class="separator"></div>
            <div class="btn-group" role="group">
                <button @click="setCurrentPage('companiesTable')" class="btn btn-primary"
                    :class="{ active: currentPage === 'companiesTable' }">
                    Companies
                </button>
            </div>
            <div class="separator"></div>
            <div class="btn-group" role="group">
                <button @click="setCurrentPage('fileUpload')" class="btn btn-primary"
                    :class="{ active: currentPage === 'fileUpload' }">
                    Upload Data
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    methods: {
        setCurrentPage(page) {
            // Emit an event to notify the parent component (App.vue) to change the current page
            this.$emit('changePage', page);
        },
    },
};
</script>

<style scoped>
.separator {
    border-right: 1px solid #ccc;
    /* Customize the separator's style */
    height: 30px;
    /* Adjust the height to match button height */
    margin: 0 10px;
    /* Adjust margin for spacing */
}

/* Add your custom styles here if needed */
</style>