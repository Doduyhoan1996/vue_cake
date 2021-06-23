<template>
    <div id="pcm-timesheet-view">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Timesheet (đang phát triển)</h1>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'pcm-timesheet-view',
    data() {
        return {
            
        }
    },
    created() {
        let self = this;
        this.$emit('activeMenu', 'timesheet');
    },
    methods: {

    }
}
</script>

