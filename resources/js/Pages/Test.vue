<template>
    <h1>Test Page</h1>
    <button @click="router.visit(companyRoutes.index)">
        Company Registration
    </button>
</template>
<script setup>
import { router } from '@inertiajs/vue3';
import { companyRoutes } from '../routes/CompanyRoutes';
</script>