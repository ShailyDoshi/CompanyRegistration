<template>
    <div
        style="
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
            background-color: #f4f6f9;
        "
    >
    <div
      style="
        width: 900px;
        height: 600px;
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: 0.3s ease;
      "
    >
        <div style="justify-content: space-between; display: flex;">
            <h2 style="margin-bottom: 10px; font-size: 20px; font-weight: bold;">
                Comapny Details
            </h2>
            <button @click="router.visit(companyRoutes.form())" style="background-color: #2563eb; color: #ffffff; border: none; border-radius: 6px;">Add Comapny</button>
        </div>
        <!-- Success Message -->
        <div 
            v-if="successMessage"
            style="
                margin-top: 15px;
                padding: 10px 15px;
                background-color: #d1fae5;
                color: #065f46;
                border-radius: 6px;
                border: 1px solid #10b981;
            "
        >
            {{ successMessage }}
        </div>

        <!-- search -->
        <div style="margin-top:20px; margin-bottom:15px;">
            <input
                v-model="search"
                type="text"
                placeholder="Search by name or email..."
                style="
                    padding:8px;
                    width:250px;
                    border:1px solid #ccc;
                    border-radius:6px;
                "
            />
        </div>
        <div 
            style="
                margin-top: 30px;
                display: flex;
                gap: 20px;
            "
        >
            <div style="margin-top: 30px;">
                <table 
                    style="
                        width: 100%;
                        border-collapse: collapse;
                        font-size: 14px;
                    "
                >
                    <thead style="background-color: #f3f4f6;">
                        <tr>
                            <th style="padding: 10px; border-bottom: 1px solid #ddd;">ID</th>
                            <th style="padding: 10px; border-bottom: 1px solid #ddd;">Logo</th>
                            <th style="padding: 10px; border-bottom: 1px solid #ddd;">Name</th>
                            <th style="padding: 10px; border-bottom: 1px solid #ddd;">Email</th>
                            <th style="padding: 10px; border-bottom: 1px solid #ddd;">Status</th>
                            <th style="padding: 10px; border-bottom: 1px solid #ddd;">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr 
                            v-for="value in data" 
                            :key="value.id"
                            style="text-align: center;"
                        >
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                {{ value.id }}
                            </td>

                            <!-- Logo Column -->
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                <img 
                                    v-if="value.logo"
                                    :src="`/storage/${value.logo}`"
                                    alt="Logo"
                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;"
                                />
                                <span v-else>No Logo</span>
                            </td>

                            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                {{ value.name }}
                            </td>

                            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                {{ value.email }}
                            </td>

                            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                <span 
                                    :style="{
                                        padding: '4px 8px',
                                        borderRadius: '6px',
                                        color: '#fff',
                                        backgroundColor: value.status ? '#16a34a' : '#dc2626'
                                    }"
                                >
                                    {{ value.status ? 'Open' : 'Close' }}
                                </span>
                            </td>

                            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                <button 
                                    @click="router.visit(companyRoutes.form(value.id))"
                                    style="margin-right: 5px;"
                                >
                                    Edit
                                </button> 

                                <button @click="handleDelete(value.id)">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- pagination -->
                <div style="margin-top: 20px; text-align: center;">
                    <button @click="getAllData(currentPage - 1)" :disabled="currentPage === 1">Prev</button>
                    <span style="margin: 0px 10px;">
                        Page {{ currentPage }} of {{ lastPage }}
                    </span>
                    <button @click="getAllData(currentPage + 1)" :disabled="currentPage === lastPage">Next</button>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>
<script setup>
import { router } from '@inertiajs/vue3';
import { companyRoutes } from '../../routes/CompanyRoutes';
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';

const data = ref([]);
const successMessage = ref('');

const currentPage = ref(1);
const lastPage = ref(1);
const perPage = ref(5);
const total = ref(0);
const search = ref('');

function getAllData(page = 1)
{
    axios.get(companyRoutes.getAllData,{
        params:{
            page:page,
            perPage:perPage.value,
            search: search.value,
        }
    })
        .then((response)=>{
            data.value = response.data.data.data;
            currentPage.value = response.data.data.current_page;
            lastPage.value = response.data.data.last_page;
            total.value = response.data.data.total;
        })
}

function handleDelete(id)
{
    if(confirm('Are You Sure You Want To Delete This Record'))
    {
        axios.get(companyRoutes.delete(id))
            .then((response)=>{
                successMessage.value = response.data.successMessage
                getAllData();
            })
    }
}

let timeOut = null;
watch(search,()=>{
    clearTimeout(timeOut);
    timeOut = setTimeout(()=>{
        getAllData(1);
    },500);
})

onMounted(()=>{
    getAllData();
})
</script>