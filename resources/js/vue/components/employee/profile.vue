<template>
    <div class="flex">
        <div class="w-1/2">
            <div>Name</div>
            <span>{{ employee.name }}</span>
        </div>

        <div class="w-1/2">
            <div>address</div>
            <span>{{ employee.address }}</span>
        </div>

        <div class="w-1/2">
            <div>department</div>
            <span>{{ employee.department.name }}</span>
        </div>

        <div class="w-1/2">
            <div>Join Date</div>
            <span>{{ employee.joined }}</span>
        </div>
    </div>
</template>

<script>

export default {
    props: {
        employee: {
            type: Object,
            required: true,
        },
    },
}

</script>